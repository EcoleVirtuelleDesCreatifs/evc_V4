<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau projet disponible</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background:#f4f4f5; margin:0; padding:0; color:#111827; }
        .container { max-width: 640px; margin: 0 auto; background: #ffffff; }
        .header { padding: 28px 24px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #fff; }
        .title { font-size: 20px; font-weight: 800; margin: 0 0 6px 0; }
        .subtitle { opacity: .9; margin: 0; }
        .content { padding: 24px; }
        .card { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 16px 0; }
        .label { font-weight: 700; color: #374151; }
        .deadline { display:inline-block; padding: 6px 10px; border-radius: 999px; background:#fef3c7; color:#92400e; font-weight:700; font-size: 13px; }
        .btn { display:inline-block; margin-top: 12px; padding: 12px 18px; background: #2563eb; color:#fff; text-decoration:none; border-radius: 10px; font-weight:700; }
        .footer { padding: 20px 24px; background:#0f172a; color:#cbd5e1; font-size: 13px; }
        .muted { color:#6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p class="title">Un nouveau projet est disponible</p>
            <p class="subtitle">École Virtuelle des Créatifs</p>
        </div>

        <div class="content">
            <p>
                Bonjour
                <strong>
                    @php
                        $fullName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                        if ($fullName === '') {
                            $fullName = $student->name ?? 'Étudiant';
                        }
                    @endphp
                    {{ $fullName }}
                </strong>,
            </p>

            <div class="card">
                <div style="font-size: 16px; font-weight: 800;">{{ $project->title }}</div>
                <div class="muted" style="margin-top: 6px;">Catégorie : {{ $project->category ?? 'N/A' }}</div>

                @if(!empty($project->deadline))
                    <div style="margin-top: 10px;">
                        <span class="label">Délai :</span>
                        <span class="deadline">
                            @php
                                try { $d = \Carbon\Carbon::parse($project->deadline)->format('d/m/Y'); } catch (\Exception $e) { $d = (string) $project->deadline; }
                            @endphp
                            {{ $d }}
                        </span>
                    </div>
                @endif

                @if(!empty($project->link))
                    <div style="margin-top: 10px;">
                        <span class="label">Lien de référence :</span>
                        <a href="{{ $project->link }}" target="_blank">{{ $project->link }}</a>
                    </div>
                @endif

                @if(!empty($project->description))
                    <div style="margin-top: 12px;" class="muted">
                        {!! $project->description !!}
                    </div>
                @endif

                <a class="btn" href="{{ $studentUrl ?? url('/evc') }}">Accéder au projet</a>
            </div>

            <p class="muted">Cet email est automatique. Si vous ne parvenez pas à accéder au projet, contactez l'administration.</p>
        </div>

        <div class="footer">
            © {{ date('Y') }} École Virtuelle des Créatifs
        </div>
    </div>
</body>
</html>
