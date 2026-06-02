<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Félicitations - Créez votre compte EVC</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
<table role="presentation" style="width: 100%; border-collapse: collapse;">
    <tr>
        <td align="center" style="padding: 40px 0;">
            <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <tr>
                    <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                        <div style="font-size: 64px; margin-bottom: 18px;">🎉</div>
                        <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700;">Félicitations !</h1>
                        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 16px;">Votre candidature a été acceptée</p>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 35px 30px;">
                        <p style="font-size: 16px; color: #333333; margin: 0 0 18px;">
                            Bonjour <strong>{{ $candidateName }}</strong>,
                        </p>

                        <p style="font-size: 15px; color: #333333; margin: 0 0 20px; line-height: 1.6;">
                            Votre candidature pour la formation <strong>{{ $formationName }}</strong> a été <strong style="color:#10B981;">acceptée</strong>.
                            Nous avons également enregistré votre paiement.
                        </p>

                        <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f8fafc; border-radius: 10px; margin: 20px 0;">
                            <tr>
                                <td style="padding: 18px;">
                                    <h3 style="margin: 0 0 12px; color: #333333; font-size: 16px;">Récapitulatif du paiement</h3>
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Montant payé :</td>
                                            <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #10b981; font-size: 14px;">{{ number_format($amount, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Type :</td>
                                            <td style="padding: 8px 0; text-align: right; color: #0f172a; font-size: 14px;">
                                                @if($installmentNumber)
                                                    Tranche {{ $installmentNumber }}
                                                @else
                                                    Paiement libre
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Méthode :</td>
                                            <td style="padding: 8px 0; text-align: right; color: #0f172a; font-size: 14px;">{{ $method ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Référence :</td>
                                            <td style="padding: 8px 0; text-align: right; color: #0f172a; font-size: 14px;"><code>{{ $reference }}</code></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Date :</td>
                                            <td style="padding: 8px 0; text-align: right; color: #0f172a; font-size: 14px;">{{ $paidAt }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 10px; margin: 20px 0;">
                            <tr>
                                <td style="padding: 18px;">
                                    <h3 style="margin: 0 0 12px; color: #333333; font-size: 16px;">Suivi de votre scolarité</h3>
                                    <table style="width: 100%; border-collapse: collapse;">
                                        @if(($discountAmount ?? 0) > 0)
                                        <tr>
                                            <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Coût formation :</td>
                                            <td style="padding: 8px 0; text-align: right; color: #64748b; font-size: 14px; text-decoration: line-through;">{{ number_format($grossTotalAmount ?? $totalAmount, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; color: #047857; font-size: 14px; font-weight: 700;">Remise appliquée :</td>
                                            <td style="padding: 8px 0; text-align: right; color: #047857; font-size: 14px; font-weight: 700;">- {{ number_format($discountAmount, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Total formation :</td>
                                            <td style="padding: 8px 0; text-align: right; color: #0f172a; font-size: 14px; font-weight: 700;">{{ number_format($totalAmount, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Total payé :</td>
                                            <td style="padding: 8px 0; text-align: right; color: #10b981; font-size: 14px; font-weight: 700;">{{ number_format($amountPaid, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Reste à payer :</td>
                                            <td style="padding: 8px 0; text-align: right; color: {{ $remaining > 0 ? '#ef4444' : '#10b981' }}; font-size: 14px; font-weight: 700;">{{ number_format($remaining, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 28px 0;">
                            <tr>
                                <td align="center">
                                    <a href="{{ $accountCreationUrl }}" style="display: inline-block; background: linear-gradient(135deg, #10b981, #059669); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 30px; font-size: 16px; font-weight: 700;">
                                        🔐 CRÉER VOTRE COMPTE EVC
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #fef3c7; border-left: 4px solid #F59E0B; margin: 25px 0;">
                            <tr>
                                <td style="padding: 14px;">
                                    <p style="color: #78350f; margin: 0; font-size: 13px; line-height: 1.5;">
                                        <strong>⏰ Important :</strong> Ce lien est à usage unique et expire dans 24 heures.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <p style="font-size: 15px; color: #333333; margin: 0;">
                            À très bientôt sur notre plateforme !<br>
                            <strong>L'équipe EVC</strong>
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #f8f9fa; padding: 25px; text-align: center; border-top: 1px solid #e1e5e9;">
                        <p style="color: #6c757d; font-size: 12px; margin: 0;">
                            École Virtuelle des Créatifs (EVC) — Abidjan, Palmeraie
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
