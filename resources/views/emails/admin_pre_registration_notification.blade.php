<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle pré-inscription</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7fafc; padding:20px; color:#111827;">
    <div style="max-width:700px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
        <!-- En-tête -->
        <div style="background:linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);color:#fff;padding:24px 20px;text-align:center;">
            <h2 style="margin:0;font-size:24px;font-weight:bold;">🔔 Nouvelle Candidature Reçue</h2>
            <p style="margin:8px 0 0 0;font-size:14px;opacity:0.95;">{{ $pre->created_at->format('d/m/Y à H:i') }}</p>
        </div>

        <div style="padding:30px 20px;">
            <p style="font-size:16px;margin:0 0 20px 0;">Bonjour,</p>

            <p style="background:#DBEAFE;border-left:4px solid #3B82F6;padding:12px 16px;margin:20px 0;border-radius:4px;">
                ✨ Une <strong>nouvelle candidature</strong> vient d'être enregistrée via le formulaire en ligne et nécessite votre examen.
            </p>

            <!-- Informations du candidat -->
            <div style="background:#F9FAFB;padding:20px;border-radius:8px;margin:24px 0;">
                <h3 style="margin:0 0 16px 0;font-size:18px;color:#111827;border-bottom:2px solid #3B82F6;padding-bottom:8px;">👤 Informations Personnelles</h3>
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="padding:8px 0;font-weight:600;width:35%;">Nom complet :</td>
                        <td style="padding:8px 0;"><strong style="color:#1E40AF;">{{ $pre->prenom }} {{ $pre->nom }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Âge :</td>
                        <td style="padding:8px 0;">{{ $pre->age }} ans (Né(e) le {{ $pre->date_naissance ? \Carbon\Carbon::parse($pre->date_naissance)->format('d/m/Y') : 'N/A' }})</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Sexe :</td>
                        <td style="padding:8px 0;">{{ $pre->sexe == 'M' ? 'Masculin' : 'Féminin' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Nationalité :</td>
                        <td style="padding:8px 0;">{{ $pre->nationalite }}</td>
                    </tr>
                </table>
            </div>

            <!-- Contact -->
            <div style="background:#F0FDF4;padding:20px;border-radius:8px;margin:24px 0;">
                <h3 style="margin:0 0 16px 0;font-size:18px;color:#111827;border-bottom:2px solid #10B981;padding-bottom:8px;">📞 Coordonnées</h3>
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="padding:8px 0;font-weight:600;width:35%;">Email :</td>
                        <td style="padding:8px 0;"><a href="mailto:{{ $pre->email }}" style="color:#3B82F6;text-decoration:none;">{{ $pre->email }}</a></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">WhatsApp :</td>
                        <td style="padding:8px 0;">{{ $pre->whatsapp }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Localisation :</td>
                        <td style="padding:8px 0;">{{ $pre->ville }}, {{ $pre->pays }}</td>
                    </tr>
                </table>
            </div>

            <!-- Formation -->
            <div style="background:#FEF3C7;padding:20px;border-radius:8px;margin:24px 0;">
                <h3 style="margin:0 0 16px 0;font-size:18px;color:#111827;border-bottom:2px solid #F59E0B;padding-bottom:8px;">🎓 Formation & Parcours</h3>
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="padding:8px 0;font-weight:600;width:35%;">Programme choisi :</td>
                        <td style="padding:8px 0;">
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
                            <strong style="color:#D97706;">{{ $formationDisplay }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Niveau actuel :</td>
                        <td style="padding:8px 0;">{{ ucfirst(str_replace('_', ' ', $pre->niveau_dans_formation ?? 'Non précisé')) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Niveau d'études :</td>
                        <td style="padding:8px 0;">{{ $pre->niveau_etude }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Domaine d'étude :</td>
                        <td style="padding:8px 0;">{{ $pre->domaine_etude }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Comment nous a connu :</td>
                        <td style="padding:8px 0;">{{ ucfirst(str_replace('_', ' ', $pre->how_known ?? 'Non précisé')) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Compétences -->
            @if($pre->competences)
            <div style="background:#EDE9FE;padding:20px;border-radius:8px;margin:24px 0;">
                <h3 style="margin:0 0 12px 0;font-size:18px;color:#111827;border-bottom:2px solid #8B5CF6;padding-bottom:8px;">💼 Compétences</h3>
                <p style="line-height:1.6;margin:0;">{{ $pre->competences }}</p>
            </div>
            @endif

            <!-- Motivation -->
            @if($pre->motivation)
            <div style="background:#FCE7F3;padding:20px;border-radius:8px;margin:24px 0;">
                <h3 style="margin:0 0 12px 0;font-size:18px;color:#111827;border-bottom:2px solid #EC4899;padding-bottom:8px;">💪 Motivation</h3>
                <p style="line-height:1.6;margin:0;white-space:pre-wrap;">{{ $pre->motivation }}</p>
            </div>
            @endif

            <!-- Équipement & Disponibilité -->
            <div style="background:#F3F4F6;padding:20px;border-radius:8px;margin:24px 0;">
                <h3 style="margin:0 0 16px 0;font-size:18px;color:#111827;border-bottom:2px solid #6B7280;padding-bottom:8px;">💻 Équipement & Disponibilité</h3>
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="padding:8px 0;font-weight:600;width:35%;">Ordinateur :</td>
                        <td style="padding:8px 0;">{{ $pre->has_computer ? '✅ Oui' : '❌ Non' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Smartphone :</td>
                        <td style="padding:8px 0;">{{ $pre->has_smartphone ? '✅ Oui' : '❌ Non' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Disponibilité :</td>
                        <td style="padding:8px 0;">{{ ucfirst(str_replace('_', ' ', $pre->disponibilite ?? 'Non précisé')) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Actions -->
            <div style="background:linear-gradient(135deg, #FF6B00 0%, #FFA500 100%);padding:20px;border-radius:8px;margin:24px 0;text-align:center;">
                <p style="margin:0 0 16px 0;color:#fff;font-size:16px;font-weight:600;">⚡ Actions à Entreprendre</p>
                <a href="http://127.0.0.1:8000/evc/app/admin/preinscriptions/{{ $pre->id }}"
                   style="display:inline-block;background:#fff;color:#FF6B00;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;margin:8px;">
                    👁️ Voir la Candidature Complète
                </a>
                <br>
                <a href="http://127.0.0.1:8000/evc/app/admin/preinscriptions"
                   style="display:inline-block;background:rgba(255,255,255,0.2);color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;margin:8px;">
                    📋 Toutes les Candidatures
                </a>
            </div>

            <p style="margin:24px 0 0 0;line-height:1.6;color:#6B7280;font-size:14px;">
                <strong>Note :</strong> Un email de confirmation a été automatiquement envoyé au candidat. Il attend maintenant votre réponse sous 24h.
            </p>

            <p style="margin:24px 0 0 0;font-weight:600;">Cordialement,<br>
            <span style="color:#3B82F6;">Le système de notification automatique EVC</span></p>
        </div>

        <!-- Footer -->
        <div style="background:#F3F4F6;padding:16px 20px;text-align:center;font-size:12px;color:#6B7280;">
            <p style="margin:0;">📧 Notification automatique - EVC Admin</p>
        </div>
    </div>
</body>
</html>
