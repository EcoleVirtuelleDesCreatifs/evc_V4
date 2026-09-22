<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendez-vous EVC — Admin</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family:'Segoe UI', Arial, sans-serif;">
@php
    $slot = $appointment->slot ?? null;
    $accent = $cancelled ? '#dc2626' : '#7c3aed';
    $icon = $cancelled ? '🚫' : '📩';
    $title = $cancelled ? 'Rendez-vous annulé' : 'Nouvelle demande de rendez-vous';
@endphp
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding:24px 12px;">
<tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%;">

        <!-- Header -->
        <tr><td style="background:linear-gradient(135deg,#0a1128 0%,#1e1b4b 60%,#4c1d95 100%); border-radius:18px 18px 0 0; padding:30px 32px; text-align:center;">
            <div style="display:inline-block; width:60px; height:60px; border-radius:50%; background:rgba(255,255,255,0.12); line-height:60px; font-size:28px; margin-bottom:10px;">{{ $icon }}</div>
            <div style="display:inline-block; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); color:#fff; font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; border-radius:999px; padding:4px 14px; margin-bottom:10px;">EVC — Administration</div>
            <h1 style="color:#ffffff; font-size:22px; font-weight:800; margin:0;">{{ $title }}</h1>
        </td></tr>

        <!-- Body -->
        <tr><td style="background:#ffffff; padding:30px 32px;">
            <p style="color:#475569; font-size:14px; line-height:1.6; margin:0 0 18px;">
                @if($cancelled)
                    <strong>{{ $student->name ?? 'Un étudiant' }}</strong> a annulé son rendez-vous.
                @else
                    <strong>{{ $student->name ?? 'Un étudiant' }}</strong> vient de demander un rendez-vous d'assistance.
                @endif
            </p>

            <!-- Card -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; margin-bottom:20px;">
                <tr><td style="padding:18px 20px;">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="color:#94a3b8; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; padding-bottom:6px;">Étudiant</td>
                        </tr>
                        <tr><td style="color:#0f172a; font-size:15px; font-weight:800; padding-bottom:2px;">{{ $student->name ?? '—' }}</td></tr>
                        <tr><td style="color:#64748b; font-size:12px; padding-bottom:12px;">{{ $student->email ?? '' }}</td></tr>
                        <tr><td style="border-top:1px dashed #e2e8f0; padding-top:12px; color:#475569; font-size:13px;">
                            📅 <strong>{{ $slotLabel }}</strong><br>
                            🎯 Motif : <strong>{{ $appointment->motif }}</strong>
                            @if($slot) &nbsp;·&nbsp; {{ $slot->mode === 'en_ligne' ? '🎥 En ligne' : '📍 Présentiel' }} @endif
                        </td></tr>
                        @if($appointment->message)
                        <tr><td style="padding-top:10px; color:#475569; font-size:13px; font-style:italic;">
                            💬 « {{ $appointment->message }} »
                        </td></tr>
                        @endif
                    </table>
                </td></tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0">
                <tr><td align="center">
                    <a href="{{ $adminUrl }}" style="display:inline-block; background:{{ $accent }}; color:#ffffff; font-size:14px; font-weight:800; text-decoration:none; border-radius:999px; padding:13px 32px;">
                        {{ $cancelled ? 'Voir les rendez-vous' : 'Traiter la demande →' }}
                    </a>
                </td></tr>
            </table>
        </td></tr>

        <!-- Footer -->
        <tr><td style="background:#0f172a; border-radius:0 0 18px 18px; padding:22px 32px; text-align:center;">
            <p style="color:#94a3b8; font-size:12px; margin:0 0 4px;">École Virtuelle des Créatifs — Administration</p>
            <p style="color:#64748b; font-size:11px; margin:0;">Email automatique, merci de ne pas y répondre.</p>
        </td></tr>
    </table>
</td></tr>
</table>
</body>
</html>
