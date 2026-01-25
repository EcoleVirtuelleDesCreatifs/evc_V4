<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#0b1220; padding:24px; font-family:Arial, Helvetica, sans-serif;">
    <tr>
        <td align="center">
            <table role="presentation" width="640" cellspacing="0" cellpadding="0" border="0" style="width:640px; max-width:640px; background:#0f172a; border:1px solid #1f2a44; border-radius:16px; overflow:hidden;">
                <tr>
                    <td style="padding:22px 24px; background:linear-gradient(135deg,#ff9800,#ff6b00);">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td style="color:#ffffff; font-size:18px; font-weight:700; letter-spacing:0.2px;">
                                    Ecole Virtuelle des Créatifs (EVC)
                                </td>
                                <td align="right" style="color:#ffffff; font-size:12px; opacity:0.9;">
                                    Plaquettes de formation
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:26px 24px 8px 24px; color:#e5e7eb;">
                        <div style="font-size:18px; font-weight:700; margin:0 0 10px 0;">Bonjour {{ $request->prenoms ?? '' }} {{ $request->nom ?? '' }},</div>
                        <div style="font-size:14px; line-height:1.6; margin:0; color:#cbd5e1;">
                            Bonne nouvelle : votre demande a été validée. Vous pouvez télécharger votre plaquette maintenant.
                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="padding:14px 24px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#0b1220; border:1px solid #1f2a44; border-radius:14px;">
                            <tr>
                                <td style="padding:16px 16px; color:#e5e7eb;">
                                    <div style="font-size:12px; color:#94a3b8; margin-bottom:6px;">Plaquette</div>
                                    <div style="font-size:16px; font-weight:700; margin:0 0 2px 0; color:#ffffff;">{{ $plaquette->title ?? '' }}</div>
                                    <div style="font-size:12px; color:#fbbf24;">{{ $plaquette->original_filename ?? '' }}</div>
                                </td>
                                <td align="right" style="padding:16px 16px;">
                                    <a href="{{ $downloadUrl }}" style="display:inline-block; padding:12px 18px; background:#22c55e; color:#ffffff; text-decoration:none; border-radius:999px; font-weight:700; font-size:13px;">
                                        Télécharger la plaquette
                                    </a>
                                    <div style="margin-top:8px; font-size:11px; color:#94a3b8;">Lien temporaire</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:6px 24px 0 24px; color:#e5e7eb;">
                        <div style="font-size:16px; font-weight:800; margin:0 0 6px 0;">Prêt(e) à vous inscrire ?</div>
                        <div style="font-size:14px; line-height:1.6; margin:0; color:#cbd5e1;">
                            Nos formations sont conçues pour vous rendre opérationnel(le) rapidement : exercices pratiques, accompagnement, et certification.
                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="padding:14px 24px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#0b1220; border:1px solid #1f2a44; border-radius:14px;">
                            <tr>
                                <td style="padding:14px 16px; color:#ffffff; font-weight:800; font-size:14px;">Tarifs & Durées</td>
                            </tr>
                            <tr>
                                <td style="padding:0 16px 16px 16px;">
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:separate; border-spacing:0 10px;">
                                        <tr>
                                            <td style="padding:12px 12px; background:#0f172a; border:1px solid #1f2a44; border-radius:12px; color:#e5e7eb;">
                                                <div style="font-weight:800;">Infographie & Design Graphique</div>
                                                <div style="font-size:12px; color:#94a3b8;">Durée : 4 mois</div>
                                            </td>
                                            <td align="right" style="padding:12px 12px; background:#0f172a; border:1px solid #1f2a44; border-radius:12px; color:#fbbf24; font-weight:900;">80 000 FCFA</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:12px 12px; background:#0f172a; border:1px solid #1f2a44; border-radius:12px; color:#e5e7eb;">
                                                <div style="font-weight:800;">Community & Social Media Management</div>
                                                <div style="font-size:12px; color:#94a3b8;">Durée : 3 mois</div>
                                            </td>
                                            <td align="right" style="padding:12px 12px; background:#0f172a; border:1px solid #1f2a44; border-radius:12px; color:#fbbf24; font-weight:900;">105 000 FCFA</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:12px 12px; background:#0f172a; border:1px solid #1f2a44; border-radius:12px; color:#e5e7eb;">
                                                <div style="font-weight:800;">Design Graphique &amp; Community Management</div>
                                                <div style="font-size:12px; color:#94a3b8;">Durée : 7 mois</div>
                                            </td>
                                            <td align="right" style="padding:12px 12px; background:#0f172a; border:1px solid #1f2a44; border-radius:12px; color:#fbbf24; font-weight:900;">165 000 FCFA</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:12px 12px; background:#0f172a; border:1px solid #1f2a44; border-radius:12px; color:#e5e7eb;">
                                                <div style="font-weight:800;">Bureautique et informatique</div>
                                                <div style="font-size:12px; color:#94a3b8;">Durée : 2 mois</div>
                                            </td>
                                            <td align="right" style="padding:12px 12px; background:#0f172a; border:1px solid #1f2a44; border-radius:12px; color:#fbbf24; font-weight:900;">150 000 FCFA</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:12px 12px; background:#0f172a; border:1px solid #1f2a44; border-radius:12px; color:#e5e7eb;">
                                                <div style="font-weight:800;">Intelligence Artificielle Appliquée</div>
                                                <div style="font-size:12px; color:#94a3b8;">Durée : 1 mois</div>
                                            </td>
                                            <td align="right" style="padding:12px 12px; background:#0f172a; border:1px solid #1f2a44; border-radius:12px; color:#fbbf24; font-weight:900;">55 000 FCFA</td>
                                        </tr>
                                    </table>
                                    <div style="margin-top:8px; font-size:12px; color:#94a3b8;">Paiement possible en une ou plusieurs tranches.</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:0 24px 22px 24px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td align="center" style="padding:10px 0 6px 0;">
                                    <a href="{{ route('preinscription.start', [], true) }}" style="display:inline-block; padding:14px 22px; background:linear-gradient(135deg,#ff9800,#ff6b00); color:#ffffff; text-decoration:none; border-radius:999px; font-weight:900; font-size:14px;">
                                        Je m'inscris maintenant
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="padding:4px 0 0 0;">
                                    <a href="{{ route('formations', [], true) }}" style="color:#93c5fd; text-decoration:none; font-size:12px;">Voir toutes les formations</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:16px 24px; background:#0b1220; border-top:1px solid #1f2a44; color:#94a3b8;">
                        <div style="font-size:12px; line-height:1.6;">
                            Merci,<br>
                            <span style="color:#e5e7eb; font-weight:800;">EVC</span>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
