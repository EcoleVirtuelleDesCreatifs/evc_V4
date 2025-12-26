<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page introuvable (404) - École Virtuelle des Créatifs</title>
    <style>
        :root {
            --evc-blue-950: #071024;
            --evc-blue-900: #0b1b3a;
            --evc-blue-700: #1e3c72;
            --evc-blue-500: #2a5298;
            --evc-orange-600: #ff8a00;
            --evc-orange-500: #ff6a00;
            --evc-text: rgba(255, 255, 255, 0.94);
            --evc-muted: rgba(255, 255, 255, 0.72);
            --evc-border: rgba(255, 255, 255, 0.14);
            --evc-card: rgba(255, 255, 255, 0.07);
            --evc-shadow: rgba(0, 0, 0, 0.36);
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Inter, Arial, sans-serif;
            background:
                radial-gradient(900px 640px at 14% 20%, rgba(42, 82, 152, 0.38), transparent 60%),
                radial-gradient(820px 560px at 86% 26%, rgba(30, 60, 114, 0.28), transparent 60%),
                radial-gradient(880px 560px at 55% 88%, rgba(255, 138, 0, 0.18), transparent 55%),
                linear-gradient(135deg, #050a14, var(--evc-blue-950));
            color: var(--evc-text);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .wrap {
            width: 100%;
            max-width: 920px;
        }

        .card {
            background: var(--evc-card);
            border: 1px solid var(--evc-border);
            border-radius: 18px;
            padding: 28px;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 18px 60px var(--evc-shadow), 0 0 0 1px rgba(255, 255, 255, 0.04) inset;
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(520px 220px at 22% 10%, rgba(42, 82, 152, 0.22), transparent 70%),
                radial-gradient(620px 260px at 84% 0%, rgba(255, 138, 0, 0.16), transparent 70%);
            opacity: 1;
            pointer-events: none;
        }

        .inner {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 26px;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .brand-badge {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--evc-blue-700), var(--evc-blue-500), var(--evc-orange-600));
            display: grid;
            place-items: center;
            font-weight: 800;
            letter-spacing: 0.5px;
            box-shadow: 0 12px 28px rgba(42, 82, 152, 0.20), 0 10px 30px rgba(255, 138, 0, 0.14);
        }

        .brand-title {
            line-height: 1.1;
        }

        .brand-title .name {
            font-weight: 800;
            font-size: 1.05rem;
        }

        .brand-title .sub {
            font-size: 0.9rem;
            color: var(--evc-muted);
        }

        .code {
            display: inline-flex;
            align-items: baseline;
            gap: 10px;
            margin: 6px 0 10px;
        }

        .code .n {
            font-size: 3rem;
            font-weight: 900;
            letter-spacing: -1.2px;
        }

        .code .label {
            font-weight: 800;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.9rem;
        }

        h1 {
            margin: 0 0 10px;
            font-size: 1.65rem;
            letter-spacing: -0.3px;
        }

        p {
            margin: 0 0 10px;
            color: var(--evc-muted);
            line-height: 1.6;
        }

        .path {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 14px;
            background: rgba(0, 0, 0, 0.24);
            border: 1px solid rgba(255, 255, 255, 0.10);
            color: rgba(255, 255, 255, 0.82);
            font-weight: 700;
            font-size: 0.95rem;
            word-break: break-word;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }

        .btn {
            appearance: none;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.08);
            color: var(--evc-text);
            padding: 12px 14px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-1px);
            border-color: rgba(255, 255, 255, 0.28);
            background: rgba(255, 255, 255, 0.12);
        }

        .btn.primary {
            border: none;
            background: linear-gradient(135deg, var(--evc-blue-700), var(--evc-blue-500), var(--evc-orange-600));
            box-shadow: 0 14px 36px rgba(42, 82, 152, 0.18), 0 16px 46px rgba(255, 138, 0, 0.16);
        }

        .btn.primary:hover {
            background: linear-gradient(135deg, var(--evc-blue-500), var(--evc-orange-600), var(--evc-orange-500));
        }

        .panel {
            border-radius: 16px;
            padding: 18px;
            background: rgba(0, 0, 0, 0.20);
            border: 1px solid rgba(255, 255, 255, 0.10);
        }

        .panel-title {
            font-weight: 800;
            margin: 0 0 8px;
            letter-spacing: -0.2px;
        }

        .panel-text {
            margin: 0;
            color: var(--evc-muted);
            font-size: 0.98rem;
            line-height: 1.65;
        }

        .panel-list {
            margin: 12px 0 0;
            padding-left: 18px;
            color: var(--evc-muted);
            line-height: 1.65;
        }

        .footer {
            margin-top: 12px;
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.85rem;
        }

        @media (max-width: 860px) {
            .inner { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <section class="card">
            <div class="inner">
                <div>
                    <div class="brand">
                        <div class="brand-badge">EVC</div>
                        <div class="brand-title">
                            <div class="name">École Virtuelle des Créatifs</div>
                            <div class="sub">Nous ne trouvons pas la page demandée</div>
                        </div>
                    </div>

                    <div class="code">
                        <div class="n">404</div>
                        <div class="label">Page introuvable</div>
                    </div>

                    <h1>On dirait que tu t’es égaré</h1>
                    <p>
                        Pas d’inquiétude : ça arrive. Repars depuis l’accueil ou reviens à la page précédente.
                        Si tu es dans l’espace admin, vérifie que tu es bien connecté.
                    </p>

                    <div class="path">
                        URL demandée : {{ request()->path() }}
                    </div>

                    <div class="actions">
                        <a class="btn primary" href="{{ url('/') }}">Retour à l’accueil</a>
                        <button class="btn" type="button" onclick="history.back();">Revenir en arrière</button>
                        <a class="btn" href="@if (\Illuminate\Support\Facades\Route::has('login')){{ route('login') }}@else{{ url('/login') }}@endif">
                            Se connecter
                        </a>
                    </div>

                    <div class="footer">
                        Si tu penses que c’est une erreur, contacte l’administration ou réessaie dans quelques minutes.
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-title">Retrouver ton chemin</div>
                    <p class="panel-text">
                        Voici quelques raccourcis utiles pour continuer sans perdre de temps.
                    </p>
                    <ul class="panel-list">
                        <li>Retourner à l’accueil et accéder à ton espace.</li>
                        <li>Vérifier l’orthographe du lien ou du favori.</li>
                        <li>Si c’est un lien reçu, demande une nouvelle URL.</li>
                    </ul>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
