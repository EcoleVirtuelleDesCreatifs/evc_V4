<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de pré-inscription</title>
</head>
<body style="margin:0;padding:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:#f5f5f5;">
    <div style="max-width:650px;margin:0 auto;background:#ffffff;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <!-- En-tête moderne avec dégradé -->
        <div style="background:linear-gradient(135deg, #FF6B00 0%, #FF8C00 50%, #FFA500 100%);padding:40px 30px;text-align:center;position:relative;">
            <div style="background:rgba(255,255,255,0.15);display:inline-block;padding:8px 20px;border-radius:25px;margin-bottom:15px;">
                <span style="color:#fff;font-size:13px;font-weight:600;letter-spacing:0.5px;">✓ CANDIDATURE REÇUE AVEC SUCCÈS</span>
            </div>
            <h1 style="margin:0;color:#fff;font-size:28px;font-weight:700;text-shadow:0 2px 4px rgba(0,0,0,0.1);">École Virtuelle des Créatifs</h1>
        </div>

        <div style="padding:40px 30px;">
            <!-- Salutation -->
            <p style="font-size:17px;color:#2c3e50;margin:0 0 25px 0;">Bonjour <strong style="color:#FF6B00;">{{ $pre->prenom }} {{ $pre->nom }}</strong>,</p>

            <p style="line-height:1.8;color:#4a5568;margin:0 0 25px 0;font-size:15px;">Nous vous remercions sincèrement d'avoir choisi l'<strong>École Virtuelle des Créatifs (EVC)</strong> pour votre parcours de formation professionnelle.</p>

            <!-- Alerte succès moderne -->
            <div style="background:linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);border-left:5px solid #2196F3;padding:20px;margin:25px 0;border-radius:8px;box-shadow:0 2px 8px rgba(33,150,243,0.15);">
                <p style="margin:0;color:#1565C0;font-size:15px;line-height:1.7;">
                    <strong style="font-size:16px;">📧 Votre candidature a été enregistrée avec succès</strong> et sera examinée par notre équipe pédagogique dans les <strong>prochaines 24 heures</strong>.
                </p>
            </div>

            <!-- Récapitulatif moderne avec icônes -->
            <div style="background:linear-gradient(135deg, #FFF8F0 0%, #FFE8D6 100%);border:2px solid #FFD4A8;padding:25px;border-radius:12px;margin:30px 0;box-shadow:0 3px 10px rgba(255,107,0,0.1);">
                <h3 style="margin:0 0 20px 0;font-size:19px;color:#FF6B00;font-weight:700;display:flex;align-items:center;">
                    <span style="background:#FF6B00;color:#fff;width:32px;height:32px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-right:12px;font-size:16px;">📋</span>
                    Récapitulatif de Votre Candidature
                </h3>
                <table style="width:100%;border-collapse:collapse;">
                    <tr style="border-bottom:1px solid #FFD4A8;">
                        <td style="padding:12px 0;font-weight:600;color:#8B4513;width:40%;font-size:14px;">Formation choisie :</td>
                        <td style="padding:12px 0;color:#2c3e50;font-weight:600;font-size:14px;">
                            @php
                                $formation = $pre->choix_formation ?? $pre->programme;
                                $formationDisplay = match($formation) {
                                    'design_graphique' => 'Design Graphique',
                                    'community_management' => 'Community Management',
                                    'design_graphique_community_management' => 'Design Graphique & Community Management',
                                    'intelligence_artificielle' => 'Intelligence Artificielle',
                                    'gestion_informatique' => 'Gestion Informatique',
                                    default => ucfirst(str_replace('_', ' ', $formation))
                                };
                            @endphp
                            {{ $formationDisplay }}
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #FFD4A8;">
                        <td style="padding:12px 0;font-weight:600;color:#8B4513;font-size:14px;">Niveau actuel :</td>
                        <td style="padding:12px 0;color:#4a5568;font-size:14px;">{{ ucfirst(str_replace('_', ' ', $pre->niveau_dans_formation ?? 'Non précisé')) }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #FFD4A8;">
                        <td style="padding:12px 0;font-weight:600;color:#8B4513;font-size:14px;">Email :</td>
                        <td style="padding:12px 0;color:#4a5568;font-size:14px;">{{ $pre->email }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #FFD4A8;">
                        <td style="padding:12px 0;font-weight:600;color:#8B4513;font-size:14px;">WhatsApp :</td>
                        <td style="padding:12px 0;color:#4a5568;font-size:14px;">{{ $pre->whatsapp }}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 0;font-weight:600;color:#8B4513;font-size:14px;">Localisation :</td>
                        <td style="padding:12px 0;color:#4a5568;font-size:14px;">{{ $pre->ville }}, {{ $pre->pays }}</td>
                    </tr>
                </table>
            </div>

            <!-- Processus de sélection moderne -->
            <div style="background:#fff;border:2px solid #e5e7eb;border-radius:12px;padding:25px;margin:30px 0;">
                <h3 style="margin:0 0 18px 0;font-size:19px;color:#2c3e50;font-weight:700;display:flex;align-items:center;">
                    <span style="background:linear-gradient(135deg, #FF6B00, #FFA500);color:#fff;width:32px;height:32px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-right:12px;font-size:16px;">🎯</span>
                    Processus de Sélection
                </h3>
                <p style="line-height:1.8;color:#4a5568;margin:0 0 18px 0;font-size:15px;">À l'EVC, nous attachons une grande importance à la <strong style="color:#FF6B00;">motivation</strong>, au <strong style="color:#FF6B00;">sérieux</strong>, à l'<strong style="color:#FF6B00;">engagement</strong> et à la <strong style="color:#FF6B00;">quête d'excellence</strong> de nos candidats. Votre candidature sera examinée selon les critères suivants :</p>
                <div style="background:#f8f9fa;padding:15px;border-radius:8px;">
                    <div style="margin:10px 0;padding-left:10px;border-left:3px solid #4CAF50;">
                        <span style="color:#2e7d32;font-weight:600;font-size:14px;">✓ Adéquation entre votre profil et la formation choisie</span>
                    </div>
                    <div style="margin:10px 0;padding-left:10px;border-left:3px solid #2196F3;">
                        <span style="color:#1565C0;font-weight:600;font-size:14px;">✓ Niveau de motivation</span>
                    </div>
                    <div style="margin:10px 0;padding-left:10px;border-left:3px solid #FF9800;">
                        <span style="color:#E65100;font-weight:600;font-size:14px;">✓ Disponibilité et engagement</span>
                    </div>
                    <div style="margin:10px 0;padding-left:10px;border-left:3px solid #9C27B0;">
                        <span style="color:#6A1B9A;font-weight:600;font-size:14px;">✓ Compétences et expériences antérieures</span>
                    </div>
                </div>
            </div>

            <!-- Deux issues possibles - Design moderne -->
            <div style="background:linear-gradient(135deg, #FFF9E6 0%, #FFF3CD 100%);border:2px solid #FFD700;border-radius:12px;padding:25px;margin:30px 0;box-shadow:0 3px 10px rgba(255,193,7,0.15);">
                <div style="text-align:center;margin-bottom:20px;">
                    <span style="background:#FFA500;color:#fff;padding:8px 20px;border-radius:25px;font-weight:700;font-size:14px;letter-spacing:0.5px;">⚠️ IMPORTANT : DEUX ISSUES POSSIBLES</span>
                </div>

                <!-- Acceptation -->
                <div style="background:#fff;border-left:5px solid #4CAF50;padding:18px;margin:15px 0;border-radius:8px;box-shadow:0 2px 6px rgba(76,175,80,0.1);">
                    <p style="margin:0 0 12px 0;font-size:16px;"><strong style="color:#2e7d32;font-size:17px;">✅ Si votre candidature est ACCEPTÉE</strong></p>
                    <p style="margin:0 0 10px 0;color:#4a5568;font-size:14px;">Vous recevrez un email de confirmation comprenant :</p>
                    <div style="background:#f1f8f4;padding:12px;border-radius:6px;">
                        <div style="margin:8px 0;padding-left:15px;color:#2e7d32;font-size:14px;">• Le lien d'accès à votre espace étudiant</div>
                        <div style="margin:8px 0;padding-left:15px;color:#2e7d32;font-size:14px;">• Les informations sur les tarifs et modalités de paiement</div>
                        <div style="margin:8px 0;padding-left:15px;color:#2e7d32;font-size:14px;">• Le calendrier de la formation</div>
                    </div>
                </div>

                <!-- Refus -->
                <div style="background:#fff;border-left:5px solid #f44336;padding:18px;margin:15px 0;border-radius:8px;box-shadow:0 2px 6px rgba(244,67,54,0.1);">
                    <p style="margin:0 0 12px 0;font-size:16px;"><strong style="color:#c62828;font-size:17px;">❌ Si votre candidature est REFUSÉE</strong></p>
                    <p style="margin:0;color:#4a5568;line-height:1.7;font-size:14px;">Nous vous communiquerons les raisons de cette décision et, si nécessaire, vous proposerons des recommandations ou alternatives pour renforcer votre dossier en vue d'une future candidature.</p>
                </div>
            </div>

            <!-- Documents à préparer - Design moderne -->
            <div style="background:#fff;border:2px solid #e5e7eb;border-radius:12px;padding:25px;margin:30px 0;">
                <h3 style="margin:0 0 18px 0;font-size:19px;color:#2c3e50;font-weight:700;display:flex;align-items:center;">
                    <span style="background:linear-gradient(135deg, #2196F3, #64B5F6);color:#fff;width:32px;height:32px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-right:12px;font-size:16px;">📄</span>
                    Documents à Préparer (en cas d'acceptation)
                </h3>
                <p style="line-height:1.8;color:#4a5568;margin:0 0 15px 0;font-size:15px;">Pour faciliter votre inscription, nous vous conseillons de préparer dès maintenant :</p>
                <div style="background:#f8f9fa;padding:15px;border-radius:8px;">
                    <div style="margin:10px 0;padding:10px;background:#fff;border-radius:6px;border-left:3px solid #FF6B00;">
                        <span style="color:#FF6B00;font-weight:600;font-size:14px;">📎 Preuve de paiement (après validation)</span>
                    </div>
                    <div style="margin:10px 0;padding:10px;background:#fff;border-radius:6px;border-left:3px solid #2196F3;">
                        <span style="color:#1565C0;font-weight:600;font-size:14px;">📎 Copie de votre pièce d'identité ou passeport</span>
                    </div>
                    <div style="margin:10px 0;padding:10px;background:#fff;border-radius:6px;border-left:3px solid #4CAF50;">
                        <span style="color:#2e7d32;font-weight:600;font-size:14px;">📎 Photo d'identité récente</span>
                    </div>
                </div>
            </div>

            <!-- Contact - Design moderne -->
            <div style="background:linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);border:2px solid #90CAF9;border-radius:12px;padding:25px;margin:30px 0;box-shadow:0 3px 10px rgba(33,150,243,0.1);">
                <h3 style="margin:0 0 18px 0;font-size:19px;color:#1565C0;font-weight:700;display:flex;align-items:center;">
                    <span style="background:#2196F3;color:#fff;width:32px;height:32px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-right:12px;font-size:16px;">💬</span>
                    Questions ou Informations Complémentaires ?
                </h3>
                <p style="margin:0 0 15px 0;color:#4a5568;font-size:15px;line-height:1.7;">N'hésitez pas à nous contacter :</p>
                <div style="background:#fff;padding:15px;border-radius:8px;">
                    <div style="margin:10px 0;">
                        <span style="color:#666;font-size:14px;">📧 Email : </span>
                        <a href="mailto:info@ecolevirtuelledescreatifs.com" style="color:#FF6B00;text-decoration:none;font-weight:600;font-size:14px;">info@ecolevirtuelledescreatifs.com</a>
                    </div>
                    <div style="margin:10px 0;">
                        <span style="color:#666;font-size:14px;">📱 WhatsApp : </span>
                        <span style="color:#2c3e50;font-weight:600;font-size:14px;">+225 07 47 25 95 07 / 07 17 25 86 02</span>
                    </div>
                </div>
            </div>

            <!-- Message de clôture -->
            <div style="margin:35px 0 0 0;padding:20px;background:#f8f9fa;border-radius:8px;text-align:center;">
                <p style="margin:0 0 15px 0;line-height:1.8;color:#4a5568;font-size:15px;">Nous vous remercions pour votre confiance et vous souhaitons bonne chance dans votre parcours de transformation professionnelle !</p>
                <p style="margin:0;font-weight:700;color:#2c3e50;font-size:16px;">Cordialement,</p>
                <p style="margin:8px 0 0 0;font-weight:700;font-size:17px;">
                    <span style="background:linear-gradient(135deg, #FF6B00, #FFA500);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">L'équipe EVC – École Virtuelle des Créatifs</span>
                </p>
            </div>
        </div>

        <!-- Footer moderne -->
        <div style="background:linear-gradient(135deg, #2c3e50 0%, #34495e 100%);padding:30px 20px;text-align:center;">
            <div style="margin-bottom:15px;">
                <p style="margin:0;color:#fff;font-size:18px;font-weight:700;letter-spacing:0.5px;">EVC - École Virtuelle des Créatifs</p>
            </div>
            <div style="border-top:2px solid rgba(255,255,255,0.2);padding-top:15px;">
                <p style="margin:8px 0;color:#bdc3c7;font-size:13px;">
                    <span style="display:inline-block;margin:0 15px;">📍 Abidjan, Côte d'Ivoire</span>
                    <span style="display:inline-block;margin:0 15px;">🌐 <a href="https://www.ecolevirtuelledescreatifs.com" style="color:#FF6B00;text-decoration:none;font-weight:600;">www.ecolevirtuelledescreatifs.com</a></span>
                </p>
                <p style="margin:12px 0 0 0;color:#95a5a6;font-size:11px;">© 2025 École Virtuelle des Créatifs. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</body>
</html>
