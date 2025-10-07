<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de pré-inscription</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7fafc; padding:20px; color:#111827;">
    <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
        <!-- En-tête -->
        <div style="background:linear-gradient(135deg, #FF6B00 0%, #FFA500 100%);color:#fff;padding:24px 20px;text-align:center;">
            <h2 style="margin:0;font-size:24px;font-weight:bold;">✅ Candidature Reçue avec Succès</h2>
            <p style="margin:8px 0 0 0;font-size:14px;opacity:0.95;">École Virtuelle des Créatifs</p>
        </div>

        <div style="padding:30px 20px;">
            <p style="font-size:16px;margin:0 0 20px 0;">Bonjour <strong>{{ $pre->prenom }} {{ $pre->nom }}</strong>,</p>
            
            <p style="line-height:1.6;">Nous vous remercions sincèrement d'avoir choisi l'<strong>École Virtuelle des Créatifs (EVC)</strong> pour votre parcours de formation professionnelle.</p>
            
            <p style="background:#DBEAFE;border-left:4px solid #3B82F6;padding:12px 16px;margin:20px 0;border-radius:4px;">
                📧 <strong>Votre candidature a été enregistrée avec succès</strong> et sera examinée par notre équipe pédagogique dans les <strong>prochaines 24 heures</strong>.
            </p>

            <!-- Récapitulatif de la candidature -->
            <div style="background:#F9FAFB;padding:20px;border-radius:8px;margin:24px 0;">
                <h3 style="margin:0 0 16px 0;font-size:18px;color:#111827;border-bottom:2px solid #FF6B00;padding-bottom:8px;">📋 Récapitulatif de Votre Candidature</h3>
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="padding:8px 0;font-weight:600;width:40%;">Formation choisie :</td>
                        <td style="padding:8px 0;">{{ ucfirst(str_replace('_', ' ', $pre->choix_formation ?? $pre->programme)) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Niveau actuel :</td>
                        <td style="padding:8px 0;">{{ ucfirst(str_replace('_', ' ', $pre->niveau_dans_formation ?? 'Non précisé')) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-weight:600;">Email :</td>
                        <td style="padding:8px 0;">{{ $pre->email }}</td>
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

            <!-- Processus de sélection -->
            <div style="margin:24px 0;">
                <h3 style="margin:0 0 12px 0;font-size:18px;color:#111827;">🎯 Processus de Sélection</h3>
                <p style="line-height:1.6;">À l'EVC, nous accordons une importance particulière à la <strong>motivation</strong>, au <strong>sérieux</strong> et à l'<strong>engagement</strong> de nos candidats. Votre dossier sera évalué selon les critères suivants :</p>
                <ul style="line-height:1.8;padding-left:20px;">
                    <li>✓ Cohérence entre votre profil et la formation choisie</li>
                    <li>✓ Motivation et projet professionnel</li>
                    <li>✓ Disponibilité et engagement</li>
                    <li>✓ Compétences et expériences préalables</li>
                </ul>
            </div>

            <!-- Deux scénarios possibles -->
            <div style="background:#FEF3C7;border-left:4px solid #F59E0B;padding:16px;margin:24px 0;border-radius:4px;">
                <p style="margin:0 0 12px 0;font-weight:600;color:#92400E;">⚠️ Important : Deux issues possibles</p>
                
                <p style="margin:12px 0;line-height:1.6;"><strong style="color:#059669;">✅ En cas d'ACCEPTATION</strong><br>
                Vous recevrez un email de validation contenant :</p>
                <ul style="margin:8px 0;padding-left:20px;line-height:1.6;">
                    <li>Les modalités de paiement et tarifs</li>
                    <li>Le calendrier de formation</li>
                    <li>La liste des documents à fournir</li>
                    <li>Le lien d'accès à votre espace étudiant</li>
                    <li>Les informations sur la rentrée</li>
                </ul>

                <p style="margin:16px 0 8px 0;line-height:1.6;"><strong style="color:#DC2626;">❌ En cas de REFUS</strong><br>
                Nous vous expliquerons les raisons de notre décision et pourrons vous proposer des alternatives ou conseils pour une future candidature.</p>
            </div>

            <!-- Documents à préparer -->
            <div style="margin:24px 0;">
                <h3 style="margin:0 0 12px 0;font-size:18px;color:#111827;">📄 Documents à Préparer (en cas d'acceptation)</h3>
                <p style="line-height:1.6;">Pour faciliter votre inscription, nous vous conseillons de préparer dès maintenant :</p>
                <ul style="line-height:1.8;padding-left:20px;">
                    <li>📎 Copie de votre pièce d'identité ou passeport</li>
                    <li>📎 Justificatif de niveau d'études (diplôme, attestation)</li>
                    <li>📎 Photo d'identité récente</li>
                    <li>📎 Preuve de paiement (après validation)</li>
                </ul>
            </div>

            <!-- Contact -->
            <div style="background:#EFF6FF;padding:16px;border-radius:8px;margin:24px 0;">
                <p style="margin:0 0 8px 0;font-weight:600;color:#1E40AF;">💬 Questions ou Informations Complémentaires ?</p>
                <p style="margin:0;line-height:1.6;">N'hésitez pas à nous contacter :<br>
                📧 Email : <a href="mailto:{{ config('mail.from.address') }}" style="color:#FF6B00;text-decoration:none;">{{ config('mail.from.address') }}</a><br>
                📱 WhatsApp : {{ $pre->whatsapp }}</p>
            </div>

            <p style="margin:24px 0 0 0;line-height:1.6;">Nous vous remercions pour votre confiance et vous souhaitons bonne chance dans votre parcours de transformation professionnelle !</p>

            <p style="margin:24px 0 0 0;font-weight:600;">Cordialement,<br>
            <span style="color:#FF6B00;">L'équipe EVC – École Virtuelle des Créatifs</span></p>
        </div>

        <!-- Footer -->
        <div style="background:#F3F4F6;padding:16px 20px;text-align:center;font-size:12px;color:#6B7280;">
            <p style="margin:0 0 8px 0;">EVC - École Virtuelle des Créatifs</p>
            <p style="margin:0;">📍 Abidjan, Côte d'Ivoire | 🌐 <a href="http://127.0.0.1:8000" style="color:#FF6B00;text-decoration:none;">www.evc.com</a></p>
        </div>
    </div>
</body>
</html>
