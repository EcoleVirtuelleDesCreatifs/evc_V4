<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page introuvable (404) - École Virtuelle des Créatifs</title>
    <style>
        :root {
            --evc-blue-900: #0b1b3a;
            --evc-blue-700: #1e3c72;
            --evc-blue-500: #2a5298;
            --evc-orange-600: #ff8a00;
            --evc-orange-500: #ff6a00;
            --evc-dark: #070f1e;
            --evc-card: rgba(255, 255, 255, 0.08);
            --evc-border: rgba(255, 255, 255, 0.14);
            --evc-text: rgba(255, 255, 255, 0.92);
            --evc-muted: rgba(255, 255, 255, 0.7);
            --evc-glow: rgba(255, 138, 0, 0.22);
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Inter, Arial, sans-serif;
            background:
                radial-gradient(900px 600px at 12% 18%, rgba(42, 82, 152, 0.55), transparent 60%),
                radial-gradient(900px 600px at 80% 30%, rgba(30, 60, 114, 0.45), transparent 60%),
                radial-gradient(900px 600px at 60% 90%, rgba(255, 138, 0, 0.28), transparent 55%),
                linear-gradient(135deg, #050912, var(--evc-dark));
            color: var(--evc-text);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .wrap {
            width: 100%;
            max-width: 920px;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 18px;
        }

        .card {
            background: var(--evc-card);
            border: 1px solid var(--evc-border);
            border-radius: 18px;
            padding: 22px;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 16px 50px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.04) inset;
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.26), rgba(255, 138, 0, 0.14));
            opacity: 0.9;
            pointer-events: none;
            mask-image: radial-gradient(420px 220px at 18% 8%, #000 20%, transparent 65%);
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
            box-shadow: 0 12px 28px rgba(42, 82, 152, 0.22), 0 10px 30px var(--evc-glow);
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
            font-size: 2.5rem;
            font-weight: 900;
            letter-spacing: -1px;
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
            font-size: 1.55rem;
            letter-spacing: -0.2px;
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
            background: rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.12);
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
            box-shadow: 0 12px 30px rgba(42, 82, 152, 0.22), 0 14px 36px var(--evc-glow);
        }

        .btn.primary:hover {
            background: linear-gradient(135deg, var(--evc-blue-500), var(--evc-orange-600), var(--evc-orange-500));
        }

        .side {
            display: grid;
            gap: 12px;
        }

        .tip {
            border-radius: 16px;
            padding: 16px;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .tip-title {
            font-weight: 800;
            margin-bottom: 6px;
        }

        .tip-text {
            margin: 0;
            color: var(--evc-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .footer {
            margin-top: 12px;
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.85rem;
        }

        @media (max-width: 860px) {
            .wrap { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <section class="card">
            <div class="brand">
                <div class="brand-badge">EVC</div>
                <div class="brand-title">
                    <div class="name">École Virtuelle des Créatifs</div>
                    <div class="sub">Erreur de navigation</div>
                </div>
            </div>

            <div class="code">
                <div class="n">404</div>
                <div class="label">Introuvable</div>
            </div>

            <h1>Oups… cette page n’existe pas</h1>
            <p>
                Le lien est peut-être incorrect, ou la page a été déplacée.
                Tu peux revenir en arrière ou retourner à l’accueil.
            </p>

            <div class="path">
                URL demandée : {{ request()->path() }}
            </div>

            <div class="actions">
                <a class="btn primary" href="{{ url('/') }}">Accueil</a>
                <button class="btn" type="button" onclick="history.back();">Retour</button>
                <a class="btn" href="@if (\Illuminate\Support\Facades\Route::has('login')){{ route('login') }}@else{{ url('/login') }}@endif">
                    Connexion
                </a>
            </div>

            <div class="footer">
                Si tu penses que c’est une erreur, contacte l’administration ou réessaie plus tard.
            </div>
        </section>

        <aside class="side">
            <div class="card">
                <div class="tip">
                    <div class="tip-title">Astuce</div>
                    <p class="tip-text">
                        Si tu arrives ici depuis un favori, essaie de retourner sur l’accueil puis de naviguer à nouveau.
                    </p>
                </div>
                <div class="tip" style="margin-top: 12px;">
                    <div class="tip-title">Tu es dans l’admin ?</div>
                    <p class="tip-text">
                        Vérifie que tu as les bons droits d’accès et que tu es connecté avec un compte admin.
                    </p>
                </div>
            </div>
        </aside>
    </main>
</body>
</html>
