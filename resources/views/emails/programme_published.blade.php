<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Programme Disponible</title>
</head>
<body style="margin:0; padding:0; background-color:#eef1f6; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-text-size-adjust:100%;">
    @php
        $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
        if ($studentName === '') { $studentName = 'Étudiant'; }
        $firstName = $student->first_name ?? $studentName;

        $rawFormation = (string) ($programme['formation'] ?? '');
        $formationLabel = ($rawFormation === 'Ciblage' || $rawFormation === '')
            ? ($student->program ?? 'Votre formation')
            : $rawFormation;
        $isTargeted = $rawFormation === 'Ciblage';
    @endphp

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#eef1f6; padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 8px 30px rgba(15,23,42,0.10);">

                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#1e3c72 0%,#2a5298 55%,#1d4ed8 100%); padding:36px 30px 30px; text-align:center;">
                            <div style="width:64px; height:64px; margin:0 auto 14px; background:rgba(255,255,255,0.14); border-radius:50%; line-height:64px; font-size:30px;">📚</div>
                            <div style="display:inline-block; background:rgba(255,255,255,0.16); border:1px solid rgba(255,255,255,0.25); color:#fff; font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; padding:5px 14px; border-radius:999px; margin-bottom:12px;">École Virtuelle des Créatifs</div>
                            <h1 style="margin:0; color:#ffffff; font-size:24px; font-weight:800; letter-spacing:-0.3px;">Nouveau Programme Disponible</h1>
                            <p style="margin:8px 0 0; color:rgba(255,255,255,0.85); font-size:14px;">Votre programme de formation vient d'être publié</p>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding:30px 32px 0;">
                            <p style="margin:0 0 6px; font-size:17px; color:#1e293b; font-weight:700;">Bonjour {{ $firstName }} 👋</p>
                            <p style="margin:0; font-size:14px; color:#475569; line-height:1.6;">
                                Un nouveau programme de formation vient d'être publié
                                @if($isTargeted) et vous a été <strong>spécialement destiné</strong>@endif.
                                Il est maintenant disponible dans votre espace étudiant.
                            </p>
                        </td>
                    </tr>

                    {{-- Programme card --}}
                    <tr>
                        <td style="padding:22px 32px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#f8faff 0%,#eef2fb 100%); border:1px solid #dbe4f5; border-left:5px solid #2a5298; border-radius:14px;">
                                <tr>
                                    <td style="padding:20px 22px;">
                                        <div style="font-size:11px; font-weight:800; letter-spacing:1px; text-transform:uppercase; color:#64748b; margin-bottom:6px;">Programme du mois</div>
                                        <div style="font-size:19px; font-weight:800; color:#1e3c72; margin-bottom:10px;">{{ $programme['titre'] }}</div>
                                        <div style="margin-bottom:4px;">
                                            <span style="display:inline-block; background:#2a5298; color:#fff; font-size:12px; font-weight:700; padding:5px 13px; border-radius:999px;">🎓 {{ $formationLabel }}</span>
                                        </div>
                                        @if(!empty($programme['description']))
                                        <div style="margin-top:12px; padding-top:12px; border-top:1px dashed #c9d6ee; font-size:13px; color:#475569; line-height:1.6;">
                                            {{ $programme['description'] }}
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- PDF info --}}
                    <tr>
                        <td style="padding:18px 32px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fff7ed; border:1px solid #fed7aa; border-radius:12px;">
                                <tr>
                                    <td width="46" style="padding:14px 0 14px 16px; font-size:22px; vertical-align:top;">📄</td>
                                    <td style="padding:14px 16px 14px 10px;">
                                        <div style="font-size:13px; font-weight:800; color:#9a3412;">Document PDF inclus</div>
                                        <div style="font-size:13px; color:#7c2d12; line-height:1.5;">Le programme complet est téléchargeable au format PDF — consultable aussi en livre numérique dans votre espace.</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- CTA --}}
                    <tr>
                        <td align="center" style="padding:28px 32px 8px;">
                            <a href="{{ $programmeUrl }}" style="display:inline-block; background:linear-gradient(135deg,#1e3c72 0%,#2a5298 100%); color:#ffffff; font-size:15px; font-weight:800; text-decoration:none; padding:15px 42px; border-radius:999px; box-shadow:0 8px 20px rgba(42,82,152,0.35);">
                                Accéder au programme →
                            </a>
                            <p style="margin:12px 0 0; font-size:12px; color:#94a3b8;">Espace étudiant → Programme</p>
                        </td>
                    </tr>

                    {{-- Steps --}}
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <div style="font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:1px; color:#64748b; margin-bottom:10px;">En 3 étapes</div>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="26" style="font-size:14px; font-weight:800; color:#2a5298; vertical-align:top;">1.</td>
                                                <td style="font-size:13px; color:#475569; padding-bottom:6px;">Connectez-vous à votre espace étudiant</td>
                                            </tr>
                                            <tr>
                                                <td width="26" style="font-size:14px; font-weight:800; color:#2a5298; vertical-align:top;">2.</td>
                                                <td style="font-size:13px; color:#475569; padding-bottom:6px;">Ouvrez la section <strong>Programme</strong></td>
                                            </tr>
                                            <tr>
                                                <td width="26" style="font-size:14px; font-weight:800; color:#2a5298; vertical-align:top;">3.</td>
                                                <td style="font-size:13px; color:#475569;">Lisez le livre numérique ou téléchargez le PDF</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Tip --}}
                    <tr>
                        <td style="padding:18px 32px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px;">
                                <tr>
                                    <td style="padding:13px 16px; font-size:13px; color:#166534; line-height:1.5;">
                                        <strong>💡 Astuce :</strong> consultez régulièrement votre espace étudiant — les dates des séances du mois y sont aussi affichées.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Signature --}}
                    <tr>
                        <td style="padding:26px 32px 30px;">
                            <p style="margin:0; font-size:14px; color:#475569; line-height:1.6;">
                                Bonne formation,<br>
                                <strong style="color:#1e3c72;">L'équipe École Virtuelle des Créatifs</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#0f172a; padding:26px 32px; text-align:center;">
                            <div style="font-size:14px; font-weight:800; color:#ffffff; margin-bottom:8px;">École Virtuelle des Créatifs (EVC)</div>
                            <div style="font-size:12px; color:#94a3b8; line-height:1.8;">
                                📍 Abidjan, Palmeraie &nbsp;•&nbsp; 📞 (+225) 07 17 25 86 02<br>
                                � WhatsApp : +225 07 47 25 95 07<br>
                                📧 <a href="mailto:info@ecolevirtuelledescreatifs.com" style="color:#93c5fd; text-decoration:none;">info@ecolevirtuelledescreatifs.com</a>
                                &nbsp;•&nbsp; 🌐 <a href="https://www.ecolevirtuelledescreatifs.com" style="color:#93c5fd; text-decoration:none;">ecolevirtuelledescreatifs.com</a>
                            </div>
                            <div style="margin-top:16px; padding-top:14px; border-top:1px solid rgba(255,255,255,0.1); font-size:11px; color:#64748b;">
                                Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
