<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature Acceptée</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <div style="font-size: 64px; margin-bottom: 20px;">🎉</div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700;">Félicitations !</h1>
                            <p style="color: #ffffff; margin: 10px 0 0; font-size: 16px;">Votre candidature a été acceptée</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="font-size: 16px; color: #333333; margin: 0 0 20px;">
                                Bonjour <strong>{{ $pre->prenom }} {{ $pre->nom }}</strong>,
                            </p>

                            <p style="font-size: 16px; color: #333333; margin: 0 0 20px;">
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
                                Nous avons le plaisir de vous annoncer que votre candidature pour la formation <strong>{{ $formationDisplay }}</strong> a été <span style="color: #10B981; font-weight: 600;">acceptée</span> ! 🎓
                            </p>

                            <!-- Success Box -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f0fdf4; border-left: 4px solid #10B981; margin: 30px 0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        @if(isset($payment->payment_type) && $payment->payment_type === 'installment')
                                {{-- Paiement par Tranche --}}
                                <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 8px; padding: 20px; margin: 20px 0;">
                                    <tr>
                                        <td>
                                            <p style="font-weight: 600; color: #1e40af; margin: 0 0 15px; font-size: 16px;">
                                                📊 Paiement Fractionné en 2 Tranches
                                            </p>
                                            <p style="color: #475569; line-height: 1.6; margin: 0 0 15px; font-size: 14px;">
                                                Pour faciliter votre inscription, vous payerez en 2 fois :
                                            </p>

                                            <table style="width: 100%; border-collapse: collapse; margin: 10px 0;">
                                                <tr style="background: #ffffff; border-bottom: 2px solid #e2e8f0;">
                                                    <td style="padding: 12px; font-weight: 600; color: #1e40af; font-size: 14px;">
                                                        1ère Tranche (À payer maintenant)
                                                    </td>
                                                    <td style="padding: 12px; text-align: right; font-weight: 700; color: #2563eb; font-size: 18px;">
                                                        {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                                    </td>
                                                </tr>
                                                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                                    <td style="padding: 12px; color: #64748b; font-size: 14px;">
                                                        2ème Tranche (Après 2 mois)
                                                    </td>
                                                    <td style="padding: 12px; text-align: right; color: #64748b; font-size: 16px;">
                                                        {{ number_format($payment->installment2_amount ?? (($payment->total_amount ?? 77000) - $payment->amount), 0, ',', ' ') }} FCFA
                                                    </td>
                                                </tr>
                                                <tr style="background: #f1f5f9;">
                                                    <td style="padding: 12px; font-weight: 700; color: #0f172a; font-size: 15px;">
                                                        Montant Total
                                                    </td>
                                                    <td style="padding: 12px; text-align: right; font-weight: 700; color: #0f172a; font-size: 19px;">
                                                        {{ number_format($payment->total_amount, 0, ',', ' ') }} FCFA
                                                    </td>
                                                </tr>
                                            </table>

                                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #fef3c7; border-left: 3px solid #f59e0b; border-radius: 6px; margin-top: 15px;">
                                                <tr>
                                                    <td style="padding: 12px;">
                                                        <p style="margin: 0; color: #92400e; font-size: 13px;">
                                                            ⚡ <strong>Important :</strong> Vous recevrez le lien pour la 2ème tranche après validation de votre premier paiement.
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <p style="font-size: 16px; color: #333333; margin: 20px 0;">
                                    Commencez par payer la 1ère tranche de <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong> en cliquant sur le bouton ci-dessous :
                                </p>

                                <!-- CTA Button Tranche -->
                                <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $paymentUrl }}" style="display: inline-block; background: linear-gradient(135deg, #2563eb, #f97316); color: #ffffff; text-decoration: none; padding: 18px 45px; border-radius: 50px; font-size: 17px; font-weight: 700; box-shadow: 0 10px 30px rgba(37, 99, 235, 0.3);">
                                                💳 Payer la 1ère Tranche ({{ number_format($payment->amount, 0, ',', ' ') }} FCFA)
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                {{-- Paiement Unique --}}
                                <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #e7f3ff; border-radius: 8px; padding: 15px; margin: 20px 0;">
                                    <tr>
                                        <td style="padding: 10px;">
                                            <p style="color: #333333; margin: 0; font-size: 14px;">
                                                Montant : <strong style="font-size: 18px;">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</strong>
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <p style="font-size: 16px; color: #333333; margin: 0 0 30px;">
                                    Pour accéder à votre espace étudiant, veuillez procéder au paiement en cliquant sur le bouton ci-dessous :
                                </p>

                                <!-- CTA Button Unique -->
                                <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $paymentUrl }}" style="display: inline-block; background: linear-gradient(135deg, #2563eb, #f97316); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 30px; font-size: 16px; font-weight: 600;">
                                                💳 Procéder au paiement
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <!-- Process Steps -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                <tr>
                                    <td>
                                        <h4 style="color: #333333; margin: 0 0 15px; font-size: 16px;">Le processus est simple :</h4>
                                        <ol style="color: #666666; line-height: 1.8; margin: 0; padding-left: 20px;">
                                            <li>Cliquez sur le bouton ci-dessus</li>
                                            <li>Choisissez votre moyen de paiement (Orange Money, MTN, Wave, Carte bancaire)</li>
                                            <li>Validez le paiement</li>
                                            <li>Recevez un email pour créer votre compte</li>
                                            <li>Accédez à votre formation !</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>

                            <!-- Warning Box -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #fef3c7; border-left: 4px solid #FBBF24; margin: 30px 0;">
                                <tr>
                                    <td style="padding: 15px;">
                                        <p style="color: #78350f; margin: 0; font-size: 14px;">
                                            <strong>⏰ Important :</strong> Ce lien de paiement expire dans 7 jours.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Formation Details -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f8f9fa; border-radius: 8px; margin: 30px 0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h4 style="color: #333333; margin: 0 0 15px; font-size: 16px;">Détails de votre formation</h4>
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="padding: 8px 0; color: #666666; font-size: 14px;">Formation :</td>
                                                <td style="padding: 8px 0; color: #333333; font-size: 14px; text-align: right; font-weight: 600;">
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
                                            <tr>
                                                <td style="padding: 8px 0; color: #666666; font-size: 14px;">Montant :</td>
                                                <td style="padding: 8px 0; color: #667eea; font-size: 14px; font-weight: 600; text-align: right;">{{ number_format($payment->amount, 0, ',', ' ') }} XOF</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #666666; font-size: 14px;">Référence :</td>
                                                <td style="padding: 8px 0; color: #333333; font-size: 14px; text-align: right;"><code>{{ $payment->payment_reference }}</code></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 16px; color: #333333; margin: 30px 0 0;">
                                Nous sommes ravis de vous accueillir parmi nous !<br>
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
