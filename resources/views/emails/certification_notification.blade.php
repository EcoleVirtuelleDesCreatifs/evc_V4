<!DOCTYPE html>
<html><head><meta charset="utf-8"></head>
<body style="margin:0;padding:20px;background:#f0f4f8;font-family:Segoe UI,Arial,sans-serif;">
<div style="max-width:620px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.1);">
<div style="background:linear-gradient(135deg,#1a3a6b,#2563eb);padding:2rem;text-align:center;">
<div style="font-size:2.5rem;margin-bottom:0.5rem;">🎓</div>
<h1 style="color:#fff;margin:0;font-size:1.5rem;">Certification Officielle</h1>
<p style="color:rgba(255,255,255,0.8);margin:0.4rem 0 0;">École Virtuelle des Créatifs</p>
</div>
<div style="padding:2rem;">
<p style="color:#1e293b;">Bonjour <strong>{{ $studentName }}</strong>,</p>
<p style="color:#374151;line-height:1.7;">Suite à votre parcours de formation au sein de l'<strong>École Virtuelle des Créatifs</strong>, vous êtes désormais éligible pour passer la certification officielle.</p>
<p style="color:#374151;line-height:1.7;">Cette évaluation vise à valider vos compétences techniques et générales, ainsi que votre compréhension des fondamentaux de la formation en <strong style="color:#1a3a6b;">{{ $formation }}</strong>.</p>
<p style="color:#374151;line-height:1.7;">Cette certification constitue une étape essentielle pour attester de vos compétences et confirmer votre niveau à l'issue de votre formation.</p>
{!! $scheduledInfo !!}
<div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-left:4px solid #2563eb;border-radius:12px;padding:1.25rem;margin:1.5rem 0;">
<h3 style="color:#1a3a6b;margin:0 0 0.75rem;">📋 Informations sur l'évaluation</h3>
<p style="color:#1e293b;margin:0.4rem 0;">⏱ <strong>Durée :</strong> {{ $duration }} minutes</p>
<p style="color:#1e293b;margin:0.4rem 0;">🎯 <strong>Note de passage :</strong> {{ $passingScore }}%</p>
<p style="color:#1e293b;margin:0.4rem 0;">🔒 <strong>Tentative :</strong> Une seule tentative autorisée</p>
</div>
<div style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border-left:4px solid #f97316;border-radius:12px;padding:1.25rem;margin:1.5rem 0;">
<h3 style="color:#c2410c;margin:0 0 0.75rem;">⚠️ Règles importantes</h3>
<p style="color:#374151;margin:0 0 0.3rem;">Veuillez lire attentivement les règles suivantes avant de commencer :</p>
<ul style="color:#374151;margin:0.5rem 0;padding-left:1.2rem;line-height:1.8;">
<li>Le chronomètre démarre uniquement dès que vous cliquez sur le bouton <strong>« Je démarre ma certification »</strong>.</li>
<li>Cette certification ne peut être passée qu'<strong>une seule fois</strong>.</li>
<li>Si le temps imparti arrive à expiration, vos réponses seront automatiquement <strong>enregistrées et soumises</strong>.</li>
<li>Ne fermez pas la page et évitez toute interruption pendant l'évaluation.</li>
<li>Vos réponses sont <strong>sauvegardées automatiquement</strong> en temps réel.</li>
</ul>
</div>
<div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-left:4px solid #2563eb;border-radius:12px;padding:1.25rem;margin:1.5rem 0;">
<h3 style="color:#1a3a6b;margin:0 0 0.5rem;">📖 Message du formateur</h3>
<p style="color:#374151;line-height:1.7;">{!! $instructions !!}</p>
</div>
<div style="text-align:center;margin:2rem 0;">
<a href="{{ $certUrl }}" style="display:inline-block;background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;padding:0.9rem 2.5rem;border-radius:30px;text-decoration:none;font-weight:700;font-size:1rem;box-shadow:0 4px 15px rgba(249,115,22,0.4);">Accéder à mes certifications</a>
</div>
<p style="color:#6b7280;font-size:0.9rem;text-align:center;">Bonne chance ! 🍀</p>
</div>
<div style="background:linear-gradient(135deg,#1a3a6b,#2563eb);padding:1rem;text-align:center;">
<p style="color:rgba(255,255,255,0.7);font-size:0.75rem;margin:0;">École Virtuelle des Créatifs — <a href="https://ecolevirtuelledescreatifs.com" style="color:#f97316;text-decoration:none;">ecolevirtuelledescreatifs.com</a></p>
</div>
</div>
</body></html>
