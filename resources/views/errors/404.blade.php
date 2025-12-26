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

            --evc-grad-a: #1e3c72;
            --evc-grad-b: #2a5298;
            --evc-grad-c: #ff8a00;
            --evc-grad-d: #ff6a00;
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Inter, Arial, sans-serif;
            background: radial-gradient(1200px 900px at 20% 15%, rgba(42, 82, 152, 0.22), transparent 60%),
                radial-gradient(1200px 900px at 80% 30%, rgba(255, 138, 0, 0.14), transparent 60%),
                linear-gradient(120deg, var(--evc-blue-950), #060c1a);
            color: var(--evc-text);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .stage {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .aurora {
            position: absolute;
            inset: -40%;
            background: linear-gradient(120deg, var(--evc-grad-a), var(--evc-grad-b), var(--evc-grad-c), var(--evc-grad-d), var(--evc-grad-b));
            background-size: 300% 300%;
            filter: blur(60px);
            opacity: 0.55;
            animation: auroraMove 10s ease-in-out infinite;
            transform: translate3d(0, 0, 0);
        }

        .grid {
            position: absolute;
            inset: 0;
            opacity: 0.16;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: radial-gradient(700px 480px at 50% 40%, #000 40%, transparent 85%);
        }

        .blob {
            position: absolute;
            width: 520px;
            height: 520px;
            border-radius: 999px;
            filter: blur(2px);
            opacity: 0.38;
            mix-blend-mode: screen;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.22), transparent 55%),
                linear-gradient(135deg, rgba(42, 82, 152, 0.85), rgba(255, 138, 0, 0.75));
            animation: blobFloat 10s ease-in-out infinite;
            transform: translate3d(0, 0, 0);
        }

        .blob.b1 {
            top: -180px;
            left: -140px;
            width: 560px;
            height: 560px;
            animation-duration: 12s;
        }

        .blob.b2 {
            bottom: -220px;
            right: -160px;
            width: 620px;
            height: 620px;
            opacity: 0.34;
            animation-duration: 14s;
            animation-delay: -3s;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.16), transparent 55%),
                linear-gradient(135deg, rgba(255, 138, 0, 0.75), rgba(42, 82, 152, 0.8));
        }

        .blob.b3 {
            top: 48%;
            left: 60%;
            width: 380px;
            height: 380px;
            opacity: 0.24;
            animation-duration: 16s;
            animation-delay: -6s;
        }

        @keyframes auroraMove {
            0% { background-position: 0% 50%; transform: rotate(0deg) scale(1); }
            50% { background-position: 100% 50%; transform: rotate(8deg) scale(1.06); }
            100% { background-position: 0% 50%; transform: rotate(0deg) scale(1); }
        }

        @keyframes blobFloat {
            0% { transform: translate3d(0, 0, 0) scale(1); border-radius: 52% 48% 55% 45% / 55% 45% 55% 45%; }
            50% { transform: translate3d(18px, -22px, 0) scale(1.05); border-radius: 60% 40% 45% 55% / 45% 55% 40% 60%; }
            100% { transform: translate3d(0, 0, 0) scale(1); border-radius: 52% 48% 55% 45% / 55% 45% 55% 45%; }
        }

        .wrap {
            width: 100%;
            max-width: 920px;
            position: relative;
            z-index: 1;
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
            font-size: 3.2rem;
            font-weight: 950;
            letter-spacing: -1.4px;
            line-height: 1;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.65));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero404 {
            display: inline-block;
            margin: 10px 0 14px;
            font-size: 4.6rem;
            font-weight: 950;
            letter-spacing: -0.06em;
            line-height: 0.95;
            background: linear-gradient(135deg, var(--evc-grad-a), var(--evc-grad-b), var(--evc-grad-c), var(--evc-grad-d));
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            filter: drop-shadow(0 18px 36px rgba(0, 0, 0, 0.35));
            animation: heroShift 5s ease-in-out infinite;
        }

        @keyframes heroShift {
            0% { background-position: 0% 50%; transform: translateY(0); }
            50% { background-position: 100% 50%; transform: translateY(-2px); }
            100% { background-position: 0% 50%; transform: translateY(0); }
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

        .btn.primary::after {
            content: '';
            position: absolute;
            inset: -40% -60%;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.55), transparent 40%);
            transform: translateX(-35%);
            opacity: 0.35;
            transition: transform 0.25s ease;
        }

        .btn.primary:hover::after {
            transform: translateX(15%);
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
            .hero404 { font-size: 3.6rem; }
        }
    </style>
</head>
<body>
    <div class="stage" aria-hidden="true">
        <div class="aurora"></div>
        <div class="blob b1"></div>
        <div class="blob b2"></div>
        <div class="blob b3"></div>
        <div class="grid"></div>
    </div>
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

                    <div class="hero404">404</div>
                    <div class="code">
                        <div class="n">Page introuvable</div>
                        <div class="label">Tu peux continuer en 1 clic</div>
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
