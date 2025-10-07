<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Projet Réel soumis - EVC Admin</title>
    <style>
        /* 🎨 DESIGN MODERNE EVC - Variables CSS */
        :root {
            --evc-primary: #2563eb;
            --evc-secondary: #ea580c;
            --evc-info: #0ea5e9;
            --evc-warning: #f59e0b;
            --evc-success: #10b981;
            --evc-dark: #1f2937;
            --evc-light: #f8fafc;
            --evc-white: #ffffff;
            --evc-text: #374151;
            --evc-text-light: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--evc-text);
            background: linear-gradient(135deg, var(--evc-dark) 0%, var(--evc-secondary) 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: var(--evc-white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
        }

        /* 🎯 HEADER MODERNE EVC */
        .header {
            background: linear-gradient(135deg, var(--evc-secondary), var(--evc-primary));
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        .evc-logo {
            position: relative;
            z-index: 2;
            margin-bottom: 20px;
        }

        .evc-brand {
            font-size: 2rem;
            font-weight: 800;
            color: var(--evc-white);
            margin-bottom: 5px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .evc-tagline {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .notification-badge {
            background: var(--evc-success);
            color: var(--evc-white);
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-block;
            margin-top: 20px;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        /* 📋 CONTENU PRINCIPAL */
        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--evc-dark);
            margin-bottom: 20px;
        }

        .message-card {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            border-left: 4px solid var(--evc-success);
            position: relative;
        }

        .student-info {
            background: var(--evc-light);
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            border: 1px solid #e5e7eb;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--evc-text);
        }

        .info-value {
            color: var(--evc-text-light);
            text-align: right;
        }

        .project-details {
            background: #fff7ed;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border: 2px solid var(--evc-secondary);
        }

        .project-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--evc-dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .project-description {
            color: var(--evc-text);
            line-height: 1.8;
            margin-top: 15px;
            padding: 15px;
            background: var(--evc-white);
            border-radius: 8px;
        }

        .action-button {
            background: linear-gradient(135deg, var(--evc-secondary), var(--evc-primary));
            color: var(--evc-white);
            padding: 16px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin: 20px 0;
            box-shadow: 0 8px 25px rgba(234, 88, 12, 0.3);
            transition: transform 0.2s;
        }

        .action-button:hover {
            transform: translateY(-2px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 25px 0;
        }

        .stat-card {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #bae6fd;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--evc-primary);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--evc-text-light);
            font-weight: 500;
        }

        .project-type-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 10px;
        }

        .badge-real {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
            border: 2px solid #fbbf24;
        }

        /* 🎨 FOOTER MODERNE */
        .footer {
            background: var(--evc-dark);
            color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            text-align: center;
        }

        .footer-brand {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--evc-white);
            margin-bottom: 10px;
        }

        .footer-text {
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .footer-copyright {
            font-size: 0.8rem;
            color: var(--evc-text-light);
            padding-top: 15px;
            border-top: 1px solid #4b5563;
            margin-top: 15px;
        }

        /* 📱 RESPONSIVE */
        @media (max-width: 600px) {
            body { padding: 10px; }
            .header { padding: 30px 20px; }
            .content { padding: 30px 20px; }
            .footer { padding: 25px 20px; }
            .evc-brand { font-size: 1.5rem; }
            .greeting { font-size: 1.1rem; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- 🎯 HEADER MODERNE EVC -->
        <div class="header">
            <div class="evc-logo">
                <div class="evc-brand">🎓 EVC - Admin</div>
                <div class="evc-tagline">Système de Gestion Pédagogique</div>
            </div>
            <div class="notification-badge">🚀 Nouveau Projet Réel Soumis</div>
        </div>

        <!-- 📋 CONTENU PRINCIPAL -->
        <div class="content">
            <div class="greeting">
                Bonjour Admin,
            </div>

            <div class="message-card">
                <p style="font-size: 1.1rem; margin-bottom: 15px;">
                    <strong>📢 Un étudiant vient de soumettre un nouveau projet réel !</strong>
                </p>
                <p style="color: var(--evc-text-light);">
                    Ce projet nécessite votre attention pour validation. Les projets réels sont des travaux importants qui démontrent les compétences pratiques des étudiants.
                </p>
            </div>

            <!-- 👤 INFORMATIONS ÉTUDIANT -->
            <div class="student-info">
                <h3 style="margin-bottom: 15px; color: var(--evc-dark); font-size: 1.1rem;">
                    👨‍🎓 Informations de l'étudiant
                </h3>
                <div class="info-row">
                    <span class="info-label">Nom complet :</span>
                    <span class="info-value">
                        @php
                            $fullName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                            if (empty($fullName)) {
                                $fullName = $student->name ?? $student->email ?? 'Étudiant';
                            }
                        @endphp
                        {{ $fullName }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email :</span>
                    <span class="info-value">{{ $student->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de soumission :</span>
                    <span class="info-value">{{ now()->format('d/m/Y à H:i') }}</span>
                </div>
            </div>

            <!-- 📝 DÉTAILS DU PROJET -->
            <div class="project-details">
                <div class="project-title">
                    <span>🎨</span> {{ $project->title }}
                </div>
                
                <div class="project-type-badge badge-real">
                    ⭐ PROJET RÉEL
                </div>

                @if($project->description)
                    <div class="project-description">
                        {{ $project->description }}
                    </div>
                @endif
                
                @if(isset($project->reference_url) && $project->reference_url)
                    <div style="margin-top: 15px; padding: 15px; background: var(--evc-white); border-radius: 8px;">
                        <strong style="color: var(--evc-dark);">🔗 Lien de référence :</strong><br>
                        <a href="{{ $project->reference_url }}" style="color: var(--evc-primary); word-break: break-all; text-decoration: underline;">{{ $project->reference_url }}</a>
                    </div>
                @endif
            </div>

            <!-- 📊 STATISTIQUES -->
            @if(isset($filesCount))
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $filesCount }}</div>
                    <div class="stat-label">Fichier(s) joint(s)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">En attente</div>
                    <div class="stat-label">Statut</div>
                </div>
            </div>
            @endif

            <!-- 🎯 BOUTON D'ACTION -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $viewUrl }}" class="action-button">
                    👁️ Consulter et Évaluer le Projet
                </a>
            </div>

            <div style="background: #fef3c7; padding: 20px; border-radius: 12px; margin-top: 20px; border-left: 4px solid var(--evc-warning);">
                <p style="font-size: 0.95rem; color: var(--evc-text); margin: 0; text-align: center;">
                    <strong>⏰ Action requise</strong><br>
                    Les projets réels sont prioritaires. Pensez à évaluer ce projet dans les plus brefs délais pour maintenir la motivation de l'étudiant.
                </p>
            </div>
        </div>

        <!-- 🎨 FOOTER MODERNE -->
        <div class="footer">
            <div class="footer-brand">École Virtuelle des Créatifs</div>
            <div class="footer-text">
                Notification automatique du système de gestion pédagogique EVC.<br>
                Cet email est réservé aux administrateurs.
            </div>
            <div class="footer-copyright">
                © {{ date('Y') }} École Virtuelle des Créatifs - Tous droits réservés
            </div>
        </div>
    </div>
</body>
</html>
