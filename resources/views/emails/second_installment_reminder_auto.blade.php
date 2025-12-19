<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2ème Tranche - EVC</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Arial', sans-serif; background-color: #f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); overflow: hidden;">

                    {{-- Header Gradient --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e40af, #3b82f6, #f97316, #fb923c); padding: 50px 40px; text-align: center;">
                            <h1 style="color: white; margin: 0; font-size: 32px; font-weight: 700;">
                                🎓 Finalisez Votre Inscription
                            </h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 16px;">
                                École Virtuelle des Créatifs
                            </p>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding: 40px;">
                            <p style="font-size: 18px; color: #0f172a; margin: 0 0 20px 0;">
                                Bonjour <strong>{{ $candidate->prenom }} {{ $candidate->nom }}</strong> ! 👋
                            </p>

                            {{-- Warning Box - URGENT --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #fee2e2; border-left: 4px solid #ef4444; border-radius: 12px; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; color: #991b1b; font-size: 18px; font-weight: 700;">
                                            ⚠️ RAPPEL IMPORTANT
                                        </p>
                                        <p style="margin: 0; color: #7f1d1d; font-size: 15px; line-height: 1.6;">
                                            Cela fait maintenant <strong>2 mois</strong> depuis votre 1er paiement. Vous avez <strong style="font-size: 17px;">{{ $daysRemaining }} jours</strong> pour régler la 2ème tranche.
                                        </p>
                                        <p style="margin: 10px 0 0 0; color: #7f1d1d; font-size: 15px; line-height: 1.6; font-weight: 600;">
                                            ⚠️ Passé ce délai, <span style="color: #dc2626;">votre compte sera automatiquement désactivé</span>.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Success Box --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #d1fae5; border-left: 4px solid #10b981; border-radius: 12px; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0; color: #065f46; font-size: 15px; line-height: 1.6;">
                                            ✅ <strong>Bonne nouvelle !</strong> Votre 1ère tranche a été payée avec succès !
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #475569; line-height: 1.8; margin: 20px 0; font-size: 16px;">
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
                                Pour <strong>éviter la désactivation</strong> et finaliser complètement votre inscription à la formation <strong style="color: #2563eb;">{{ $formationDisplay }}</strong>, il vous reste à régler la 2ème et dernière tranche <strong style="color: #dc2626;">dans les {{ $daysRemaining }} jours</strong>.
                            </p>

                            {{-- Payment Amount --}}
                            <table style="width: 100%; margin: 30px 0; border-collapse: collapse; border-radius: 12px; overflow: hidden;">
                                <tr style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                                    <td style="padding: 20px; text-align: center;">
                                        <p style="margin: 0 0 8px 0; color: #64748b; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">
                                            2ème Tranche
                                        </p>
                                        <p style="margin: 0; color: #2563eb; font-size: 42px; font-weight: 800;">
                                            {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Payment Details --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f8fafc; border-radius: 12px; padding: 20px; margin: 20px 0;">
                                <tr>
                                    <td>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="padding: 10px 0; color: #64748b; font-size: 14px;">
                                                    📋 Référence :
                                                </td>
                                                <td style="padding: 10px 0; text-align: right;">
                                                    <code style="background: #e2e8f0; padding: 6px 12px; border-radius: 6px; font-size: 13px; color: #1e40af; font-weight: 600;">{{ $payment->payment_reference }}</code>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px 0; color: #64748b; font-size: 14px;">
                                                    ⏱️ Date limite :
                                                </td>
                                                <td style="padding: 10px 0; text-align: right; color: #0f172a; font-weight: 600; font-size: 14px;">
                                                    {{ \Carbon\Carbon::parse($payment->expires_at)->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Info Alert --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 12px; margin: 25px 0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0; color: #1e40af; font-size: 14px; line-height: 1.6;">
                                            💡 <strong>Astuce :</strong> Une fois cette dernière tranche payée, vous recevrez immédiatement vos identifiants de connexion pour accéder à votre plateforme de formation.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #333333; font-size: 16px; margin: 30px 0 20px 0; text-align: center;">
                                Cliquez sur le bouton ci-dessous pour procéder au paiement :
                            </p>

                            {{-- CTA Button --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $paymentUrl }}"
                                           style="display: inline-block; background: linear-gradient(135deg, #2563eb, #f97316); color: white; padding: 20px 50px; text-decoration: none; border-radius: 50px; font-size: 18px; font-weight: 700; box-shadow: 0 15px 40px rgba(37, 99, 235, 0.4); transition: all 0.3s ease;">
                                            💳 Payer la 2ème Tranche ({{ number_format($payment->amount, 0, ',', ' ') }} FCFA)
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Process Steps --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                <tr>
                                    <td>
                                        <h3 style="color: #0f172a; margin: 0 0 15px; font-size: 17px; font-weight: 600;">
                                            📝 Le processus est simple :
                                        </h3>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="padding: 12px; background: #f8fafc; border-radius: 8px; margin-bottom: 8px;">
                                                    <span style="display: inline-block; width: 28px; height: 28px; background: #2563eb; color: white; border-radius: 50%; text-align: center; line-height: 28px; font-weight: 700; margin-right: 12px;">1</span>
                                                    <span style="color: #475569; font-size: 14px;">Cliquez sur le bouton ci-dessus</span>
                                                </td>
                                            </tr>
                                            <tr><td style="height: 8px;"></td></tr>
                                            <tr>
                                                <td style="padding: 12px; background: #f8fafc; border-radius: 8px; margin-bottom: 8px;">
                                                    <span style="display: inline-block; width: 28px; height: 28px; background: #2563eb; color: white; border-radius: 50%; text-align: center; line-height: 28px; font-weight: 700; margin-right: 12px;">2</span>
                                                    <span style="color: #475569; font-size: 14px;">Choisissez votre moyen de paiement (Orange Money, MTN, Wave, Carte bancaire)</span>
                                                </td>
                                            </tr>
                                            <tr><td style="height: 8px;"></td></tr>
                                            <tr>
                                                <td style="padding: 12px; background: #f8fafc; border-radius: 8px; margin-bottom: 8px;">
                                                    <span style="display: inline-block; width: 28px; height: 28px; background: #2563eb; color: white; border-radius: 50%; text-align: center; line-height: 28px; font-weight: 700; margin-right: 12px;">3</span>
                                                    <span style="color: #475569; font-size: 14px;">Validez le paiement de {{ number_format($payment->amount, 0, ',', ' ') }} FCFA</span>
                                                </td>
                                            </tr>
                                            <tr><td style="height: 8px;"></td></tr>
                                            <tr>
                                                <td style="padding: 12px; background: linear-gradient(135deg, #d1fae5, #a7f3d0); border-radius: 8px;">
                                                    <span style="display: inline-block; width: 28px; height: 28px; background: #10b981; color: white; border-radius: 50%; text-align: center; line-height: 28px; font-weight: 700; margin-right: 12px;">✓</span>
                                                    <span style="color: #065f46; font-size: 14px; font-weight: 600;">Recevez vos identifiants et accédez à votre formation !</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Support --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 40px 0 20px 0;">
                                <tr>
                                    <td style="border-top: 1px solid #e2e8f0; padding-top: 20px;">
                                        <p style="color: #64748b; font-size: 14px; margin: 0; line-height: 1.6;">
                                            💬 Besoin d'aide ? Notre équipe est à votre disposition.<br>
                                            Répondez simplement à cet email ou contactez-nous.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background: #f8fafc; padding: 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 10px 0; color: #0f172a; font-weight: 600; font-size: 15px;">
                                École Virtuelle des Créatifs
                            </p>
                            <p style="margin: 0; color: #64748b; font-size: 13px;">
                                Formez-vous aux métiers du digital et de la création
                            </p>
                            <p style="margin: 15px 0 0 0; color: #94a3b8; font-size: 12px;">
                                © {{ date('Y') }} EVC. Tous droits réservés.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
