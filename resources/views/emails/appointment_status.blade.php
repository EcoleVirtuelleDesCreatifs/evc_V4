<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendez-vous EVC</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family:'Segoe UI', Arial, sans-serif;">
@php
    $slot = $appointment->slot ?? null;
    $config = [
        'scheduled' => ['icon' => 'EVC', 'color' => '#2563eb', 'title' => 'Invitation EVC', 'intro' => 'L’équipe EVC a programmé une séance ou un rendez-vous pour vous. Retrouvez ci-dessous le thème, les consignes et les informations pour y participer.'],
        'confirmed' => ['icon' => '✅', 'color' => '#16a34a', 'title' => 'Rendez-vous confirmé !', 'intro' => 'Bonne nouvelle : votre demande de rendez-vous a été confirmée par l\'équipe EVC.'],
        'cancelled' => ['icon' => '❌', 'color' => '#dc2626', 'title' => 'Rendez-vous annulé', 'intro' => 'Votre rendez-vous a été annulé par l\'équipe EVC. Vous pouvez réserver un autre créneau.'],
        'completed' => ['icon' => '🏁', 'color' => '#2563eb', 'title' => 'Rendez-vous terminé', 'intro' => 'Votre rendez-vous est terminé. Merci pour votre participation !'],
        'modified' => ['icon' => '✏️', 'color' => '#d97706', 'title' => 'Rendez-vous modifié', 'intro' => 'Votre rendez-vous a été modifié par l\'équipe EVC. Vérifiez les nouveaux détails ci-dessous.'],
    ];
    $c = $config[$status] ?? $config['confirmed'];
@endphp
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding:24px 12px;">
<tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%;">

        <!-- Header -->
        <tr><td style="background:linear-gradient(135deg,#0a1128 0%,#001f54 60%,#034078 100%); border-radius:18px 18px 0 0; padding:32px 32px 28px; text-align:center;">
            <div style="display:inline-block; width:64px; height:64px; border-radius:50%; background:rgba(255,255,255,0.12); line-height:64px; font-size:30px; margin-bottom:12px;">{{ $c['icon'] }}</div>
            <div style="display:inline-block; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); color:#fff; font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; border-radius:999px; padding:4px 14px; margin-bottom:10px;">École Virtuelle des Créatifs</div>
            <h1 style="color:#ffffff; font-size:24px; font-weight:800; margin:0;">{{ $c['title'] }}</h1>
            <p style="color:rgba(255,255,255,0.75); font-size:14px; margin:8px 0 0;">{{ $appointment->motif }}</p>
        </td></tr>

        <!-- Body -->
        <tr><td style="background:#ffffff; padding:32px;">
            <p style="color:#0f172a; font-size:16px; margin:0 0 6px;">Bonjour <strong>{{ $student->first_name ?? $user->name ?? '' }}</strong> 👋</p>
            <p style="color:#475569; font-size:14px; line-height:1.6; margin:0 0 20px;">{{ $c['intro'] }}</p>

            <!-- Card RDV -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; margin-bottom:20px;">
                <tr><td style="padding:20px;">
                    <div style="color:#94a3b8; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">Votre séance / rendez-vous</div>
                    <div style="color:#0f172a; font-size:17px; font-weight:800; margin-bottom:10px;">{{ $appointment->motif }}</div>
                    @if($slot)
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="color:#475569; font-size:13px; padding:3px 0;">
                                📅 <strong>{{ $slot->date->translatedFormat('l j F Y') }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#475569; font-size:13px; padding:3px 0;">
                                🕐 {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                &nbsp;·&nbsp; {{ $slot->mode === 'en_ligne' ? '🎥 En ligne' : '📍 Présentiel' }}
                            </td>
                        </tr>
                    </table>
                    @endif
                    @if($slot && $slot->mode === 'presentiel' && $slot->lieu)
                        <p style="color:#475569; font-size:13px;">Lieu : {{ $slot->lieu }}</p>
                    @endif
                    @if($appointment->message)
                        <div style="margin-top:16px; border-top:1px solid #e2e8f0; padding-top:12px;">
                            <strong style="color:#0f172a; font-size:13px;">Programme et consignes</strong>
                            <div style="color:#475569; font-size:14px; line-height:1.6; white-space:pre-line; overflow-wrap:anywhere;">{{ $appointment->message }}</div>
                        </div>
                    @endif
                    @if($appointment->admin_note)
                    <div style="margin-top:12px; border-top:1px dashed #e2e8f0; padding-top:12px; color:#475569; font-size:13px;">
                        💬 <em>{{ $appointment->admin_note }}</em>
                    </div>
                    @endif
                </td></tr>
            </table>

            @if(in_array($status, ['scheduled', 'confirmed', 'modified']) && $appointment->meet_link && $slot?->mode === 'en_ligne')
            <!-- Lien réunion -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff7ed; border:1px solid #fed7aa; border-radius:12px; margin-bottom:20px;">
                <tr><td style="padding:16px 20px; text-align:center;">
                    <div style="color:#9a3412; font-size:12px; font-weight:700; margin-bottom:8px;">🎥 Lien de la réunion en ligne</div>
                    <a href="{{ $appointment->meet_link }}" style="display:inline-block; background:#2563eb; color:#ffffff; font-size:14px; font-weight:800; text-decoration:none; border-radius:999px; padding:12px 28px;">Rejoindre la réunion →</a>
                </td></tr>
            </table>
            @endif

            <table width="100%" cellpadding="0" cellspacing="0">
                <tr><td align="center" style="padding-top:6px;">
                    <a href="{{ $appointmentsUrl }}" style="display:inline-block; background:linear-gradient(135deg,#f97316,#ea580c); color:#ffffff; font-size:14px; font-weight:800; text-decoration:none; border-radius:999px; padding:13px 32px;">Voir mes séances et rendez-vous</a>
                </td></tr>
            </table>
        </td></tr>

        <!-- Footer -->
        <tr><td style="background:#0f172a; border-radius:0 0 18px 18px; padding:24px 32px; text-align:center;">
            <p style="color:#94a3b8; font-size:12px; margin:0 0 6px;">École Virtuelle des Créatifs — Abidjan, Côte d'Ivoire</p>
            <p style="color:#64748b; font-size:11px; margin:0;">Ceci est un email automatique, merci de ne pas y répondre.</p>
        </td></tr>
    </table>
</td></tr>
</table>
</body>
</html>
