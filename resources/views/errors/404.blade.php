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
            --evc-card: rgba(255, 255, 255, 0.06);
            --evc-shadow: rgba(0, 0, 0, 0.38);
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Inter, Arial, sans-serif;
            background:
                radial-gradient(900px 520px at 20% 18%, rgba(42, 82, 152, 0.18), transparent 62%),
                radial-gradient(900px 520px at 80% 26%, rgba(255, 138, 0, 0.10), transparent 62%),
                linear-gradient(135deg, #060b16, var(--evc-blue-950));
            color: var(--evc-text);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .wrap {
            width: 100%;
            max-width: 860px;
            position: relative;
            z-index: 1;
        }

        .card {
            background: var(--evc-card);
            border: 1px solid var(--evc-border);
            border-radius: 18px;
            padding: 30px;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 18px 60px var(--evc-shadow), 0 0 0 1px rgba(255, 255, 255, 0.04) inset;
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--evc-blue-700), var(--evc-blue-500), var(--evc-orange-600), var(--evc-orange-500));
            pointer-events: none;
        }

        .card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 18px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(42, 82, 152, 0.55), rgba(255, 138, 0, 0.45));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.65;
            pointer-events: none;
        }

        .inner {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
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

        .headline {
            display: flex;
            align-items: baseline;
            gap: 14px;
            margin: 6px 0 8px;
        }

        .headline .n {
            font-size: 3.25rem;
            font-weight: 900;
            letter-spacing: -0.06em;
            line-height: 1;
        }

        .headline .label {
            font-weight: 800;
            color: rgba(255, 255, 255, 0.82);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.85rem;
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
            transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-1px);
            border-color: rgba(255, 255, 255, 0.28);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.28);
        }

        .btn.primary {
            border: none;
            background: linear-gradient(135deg, var(--evc-blue-700), var(--evc-blue-500), var(--evc-orange-600));
            box-shadow: 0 14px 36px rgba(42, 82, 152, 0.18), 0 16px 46px rgba(255, 138, 0, 0.16);
            position: relative;
            overflow: hidden;
        }

        .btn.primary:hover {
            background: linear-gradient(135deg, var(--evc-blue-500), var(--evc-orange-600), var(--evc-orange-500));
        }

        .footer {
            margin-top: 12px;
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.85rem;
        }

        @media (max-width: 860px) {
            .inner { grid-template-columns: 1fr; }
            .headline { flex-direction: column; align-items: flex-start; gap: 6px; }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <section class="card">
            <div class="inner">
                <div class="brand">
                    <div class="brand-badge">EVC</div>
                    <div class="brand-title">
                        <div class="name">École Virtuelle des Créatifs</div>
                        <div class="sub">Page introuvable</div>
                    </div>
                </div>

                <div class="headline">
                    <div class="n">404</div>
                    <div class="label">Erreur</div>
                </div>

                <h1>Cette page n’existe pas (ou a été déplacée)</h1>
                <p>
                    Tu peux retourner à l’accueil, revenir en arrière, ou te connecter pour accéder à ton espace.
                </p>

                <div class="path">
                    URL demandée : {{ request()->path() }}
                </div>

                <div class="actions">
                    <a class="btn primary" href="{{ url('/') }}">Accueil</a>
                    <button class="btn" type="button" onclick="history.back();">Retour</button>
                    <a class="btn" href="@if (\Illuminate\Support\Facades\Route::has('login')){{ route('login') }}@else{{ url('/login') }}@endif">Connexion</a>
                </div>

                <div class="footer">
                    Besoin d’aide ? Contacte l’administration.
                </div>
            </div>
        </section>
    </main>
</body>
</html>
