<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
            padding: 20px;
            margin: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 30px;
            text-align: center;
            color: white;
            border-top: 4px solid #f97316;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
        }
        .notification-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            margin: 0 auto 20px;
        }
        .notification-content {
            background: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .notification-content h3 {
            color: #1e40af;
            margin-top: 0;
        }
        .notification-content p {
            color: #475569;
            line-height: 1.6;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table td:first-child {
            font-weight: 600;
            color: #64748b;
            width: 40%;
        }
        .data-table td:last-child {
            color: #1e293b;
        }
        .btn-action {
            display: inline-block;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
        }
        .email-footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>{{ $subject }}</h1>
            <p style="margin: 5px 0 0; opacity: 0.9;">École Virtuelle des Créatifs</p>
        </div>

        <div class="email-body">
            @if($notificationType === 'new_registration')
                <div class="notification-icon">👤</div>
                <div class="notification-content">
                    <h3>Nouvelle inscription</h3>
                    <p>Un nouvel étudiant vient de s'inscrire sur la plateforme.</p>
                </div>

                @if(isset($data['student']))
                <table class="data-table">
                    <tr>
                        <td>Nom complet</td>
                        <td>{{ $data['student']['name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $data['student']['email'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Formation</td>
                        <td>{{ $data['student']['formation'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>{{ $data['student']['date'] ?? now()->format('d/m/Y à H:i') }}</td>
                    </tr>
                </table>
                @endif

            @elseif($notificationType === 'new_payment')
                <div class="notification-icon">💳</div>
                <div class="notification-content">
                    <h3>Nouveau paiement reçu</h3>
                    <p>Un paiement a été effectué sur la plateforme.</p>
                </div>

                @if(isset($data['payment']))
                <table class="data-table">
                    <tr>
                        <td>Étudiant</td>
                        <td>{{ $data['payment']['student_name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Montant</td>
                        <td><strong style="color: #22c55e;">{{ $data['payment']['amount'] ?? 'N/A' }} FCFA</strong></td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td>{{ $data['payment']['type'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>{{ $data['payment']['date'] ?? now()->format('d/m/Y à H:i') }}</td>
                    </tr>
                </table>
                @endif

            @elseif($notificationType === 'document_submitted')
                <div class="notification-icon">📄</div>
                <div class="notification-content">
                    <h3>Document soumis</h3>
                    <p>Un étudiant a soumis un nouveau document.</p>
                </div>

                @if(isset($data['document']))
                <table class="data-table">
                    <tr>
                        <td>Étudiant</td>
                        <td>{{ $data['document']['student_name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Type de document</td>
                        <td>{{ $data['document']['type'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Nom du fichier</td>
                        <td>{{ $data['document']['filename'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>{{ $data['document']['date'] ?? now()->format('d/m/Y à H:i') }}</td>
                    </tr>
                </table>
                @endif

            @elseif($notificationType === 'project_completed')
                <div class="notification-icon">✅</div>
                <div class="notification-content">
                    <h3>Projet terminé</h3>
                    <p>Un étudiant a terminé un projet.</p>
                </div>

                @if(isset($data['project']))
                <table class="data-table">
                    <tr>
                        <td>Étudiant</td>
                        <td>{{ $data['project']['student_name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Projet</td>
                        <td>{{ $data['project']['title'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>{{ $data['project']['date'] ?? now()->format('d/m/Y à H:i') }}</td>
                    </tr>
                </table>
                @endif

            @elseif($notificationType === 'system_alert')
                <div class="notification-icon">⚠️</div>
                <div class="notification-content">
                    <h3>Alerte système</h3>
                    <p>{{ $data['message'] ?? 'Une alerte système nécessite votre attention.' }}</p>
                </div>

            @elseif($notificationType === 'backup_completed')
                <div class="notification-icon">💾</div>
                <div class="notification-content">
                    <h3>Sauvegarde effectuée</h3>
                    <p>Une sauvegarde automatique a été effectuée avec succès.</p>
                </div>

                @if(isset($data['backup']))
                <table class="data-table">
                    <tr>
                        <td>Taille</td>
                        <td>{{ $data['backup']['size'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>{{ $data['backup']['date'] ?? now()->format('d/m/Y à H:i') }}</td>
                    </tr>
                </table>
                @endif

            @elseif($notificationType === 'weekly_report')
                <div class="notification-icon">📊</div>
                <div class="notification-content">
                    <h3>Rapport hebdomadaire</h3>
                    <p>Voici le résumé de l'activité de cette semaine.</p>
                </div>

                @if(isset($data['stats']))
                <table class="data-table">
                    <tr>
                        <td>Nouvelles inscriptions</td>
                        <td><strong>{{ $data['stats']['registrations'] ?? 0 }}</strong></td>
                    </tr>
                    <tr>
                        <td>Paiements reçus</td>
                        <td><strong>{{ $data['stats']['payments'] ?? 0 }}</strong></td>
                    </tr>
                    <tr>
                        <td>Documents soumis</td>
                        <td><strong>{{ $data['stats']['documents'] ?? 0 }}</strong></td>
                    </tr>
                    <tr>
                        <td>Projets terminés</td>
                        <td><strong>{{ $data['stats']['projects'] ?? 0 }}</strong></td>
                    </tr>
                </table>
                @endif

            @elseif($notificationType === 'team_activity')
                <div class="notification-icon">👥</div>
                <div class="notification-content">
                    <h3>Activité d'équipe</h3>
                    <p>{{ $data['message'] ?? 'Un membre de l\'équipe a effectué une action.' }}</p>
                </div>

                @if(isset($data['admin']))
                <table class="data-table">
                    <tr>
                        <td>Administrateur</td>
                        <td>{{ $data['admin']['name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Action</td>
                        <td>{{ $data['admin']['action'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>{{ $data['admin']['date'] ?? now()->format('d/m/Y à H:i') }}</td>
                    </tr>
                </table>
                @endif
            @endif

            @if(isset($data['action_url']))
            <div style="text-align: center;">
                <a href="{{ $data['action_url'] }}" class="btn-action">Voir les détails</a>
            </div>
            @endif
        </div>

        <div class="email-footer">
            <p>Cet email a été envoyé automatiquement selon vos préférences de notifications.</p>
            <p style="margin: 5px 0;">
                <a href="{{ url('/evc/app/admin/parametres/notifications') }}" style="color: #f97316; text-decoration: none;">
                    Gérer mes notifications
                </a>
            </p>
            <p style="margin: 10px 0 0;">© {{ date('Y') }} École Virtuelle des Créatifs</p>
        </div>
    </div>
</body>
</html>
