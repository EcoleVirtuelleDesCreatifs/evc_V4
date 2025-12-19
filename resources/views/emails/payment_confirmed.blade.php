<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Confirmé</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 40px 30px; text-align: center;">
                            <div style="width: 80px; height: 80px; background-color: white; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 48px;">✅</span>
                            </div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700;">Paiement Confirmé !</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="font-size: 16px; color: #333333; margin: 0 0 20px;">
                                Bonjour <strong>{{ $candidate->prenom }} {{ $candidate->nom }}</strong>,
                            </p>

                            <p style="font-size: 16px; color: #333333; margin: 0 0 20px;">
                                @php
                                    $formation = $candidate->choix_formation ?? $candidate->programme;
                                    $formationDisplay = match($formation) {
                                        'design_graphique' => 'Design Graphique',
                                        'community_management' => 'Community Management',
                                        'design_graphique_community_management' => 'Design Graphique & Community Management',
                                        'intelligence_artificielle' => 'Intelligence Artificielle',
                                        'gestion_informatique' => 'Gestion Informatique',
                                        default => ucfirst(str_replace('_', ' ', $formation))
                                    };
                                @endphp
                                Excellent ! Nous avons bien reçu votre paiement de <strong style="color: #10b981;">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</strong> pour la formation <strong>{{ $formationDisplay }}</strong>. 🎉
                            </p>

                            <!-- Success Box -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f0fdf4; border-left: 4px solid #10b981; margin: 30px 0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #10b981; margin: 0 0 10px; font-size: 18px;">
                                            🔐 Dernière étape : Créez votre compte
                                        </h3>
                                        <p style="color: #333333; margin: 0; font-size: 14px;">
                                            Pour accéder à votre espace étudiant, créez votre compte en choisissant votre mot de passe.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $accountCreationUrl }}" style="display: inline-block; background: linear-gradient(135deg, #10b981, #059669); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 30px; font-size: 16px; font-weight: 600;">
                                            🔐 Créer mon compte maintenant
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Steps -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                <tr>
                                    <td>
                                        <h4 style="color: #333333; margin: 0 0 15px; font-size: 16px;">Sur cette page, vous pourrez :</h4>
                                        <ul style="color: #666666; line-height: 1.8; margin: 0; padding-left: 20px;">
                                            <li>✅ Choisir votre mot de passe sécurisé</li>
                                            <li>✅ Ajouter votre photo de profil</li>
                                            <li>✅ Activer votre accès à la formation</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>

                            <!-- Warning Box -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #fef3c7; border-left: 4px solid #FBBF24; margin: 30px 0;">
                                <tr>
                                    <td style="padding: 15px;">
                                        <p style="color: #78350f; margin: 0; font-size: 14px;">
                                            <strong>⏰ Important :</strong> Ce lien est à usage unique et doit être utilisé pour créer votre compte.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Payment Details -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f8f9fa; border-radius: 8px; margin: 30px 0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h4 style="color: #333333; margin: 0 0 15px; font-size: 16px;">Détails de votre paiement</h4>
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="padding: 8px 0; color: #666666; font-size: 14px;">Référence :</td>
                                                <td style="padding: 8px 0; color: #333333; font-size: 14px; text-align: right;"><code>{{ $payment->payment_reference }}</code></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #666666; font-size: 14px;">Montant :</td>
                                                <td style="padding: 8px 0; color: #10b981; font-size: 14px; font-weight: 600; text-align: right;">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #666666; font-size: 14px;">Formation :</td>
                                                <td style="padding: 8px 0; color: #333333; font-size: 14px; text-align: right;">
                                                    @php
                                                        $formation = $candidate->choix_formation ?? $candidate->programme;
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
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 16px; color: #333333; margin: 30px 0 0;">
                                À très bientôt sur notre plateforme !<br>
                                <strong>L'équipe EVC</strong> 🎓
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e1e5e9;">
                            <p style="color: #6c757d; font-size: 14px; margin: 0 0 10px;">
                                <strong>École Virtuelle des Créatifs (EVC)</strong>
                            </p>
                            <p style="color: #6c757d; font-size: 12px; margin: 0 0 10px;">
                                📍 Abidjan, Palmeraie<br>
                                📞 (+225) 07 17 25 86 02 | 📱 WhatsApp: +225 07 47 25 95 07<br>
                                📧 info@ecolevirtuelledescreatifs.com | contact@ecolevirtuelledescreatifs.com
                            </p>
                            <p style="color: #6c757d; font-size: 12px; margin: 0;">
                                <a href="https://www.ecolevirtuelledescreatifs.com" style="color: #667eea; text-decoration: none;">www.ecolevirtuelledescreatifs.com</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
