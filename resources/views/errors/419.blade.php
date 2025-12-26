<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page expirée (419) - École Virtuelle des Créatifs</title>
    <style>
        :root {
            --evc-purple: #833AB4;
            --evc-pink: #C13584;
            --evc-red: #E1306C;
            --evc-orange: #F56040;
            --evc-dark: #0b1220;
            --evc-card: rgba(255, 255, 255, 0.08);
            --evc-border: rgba(255, 255, 255, 0.14);
            --evc-text: rgba(255, 255, 255, 0.92);
            --evc-muted: rgba(255, 255, 255, 0.7);
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Inter, Arial, sans-serif;
            background:
                radial-gradient(1200px 700px at 20% 15%, rgba(131, 58, 180, 0.45), transparent 60%),
                radial-gradient(1200px 700px at 80% 35%, rgba(193, 53, 132, 0.35), transparent 60%),
                radial-gradient(900px 600px at 55% 90%, rgba(245, 96, 64, 0.25), transparent 55%),
                linear-gradient(135deg, #060a12, var(--evc-dark));
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
            box-shadow: 0 16px 50px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            position: relative;
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
            background: linear-gradient(135deg, var(--evc-purple), var(--evc-pink), var(--evc-red));
            display: grid;
            place-items: center;
            font-weight: 800;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 26px rgba(193, 53, 132, 0.28);
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
        }

        .btn:hover {
            transform: translateY(-1px);
            border-color: rgba(255, 255, 255, 0.28);
            background: rgba(255, 255, 255, 0.12);
        }

        .btn.primary {
            border: none;
            background: linear-gradient(135deg, var(--evc-purple), var(--evc-pink), var(--evc-red));
            box-shadow: 0 12px 30px rgba(193, 53, 132, 0.22);
        }

        .btn.primary:hover {
            background: linear-gradient(135deg, var(--evc-pink), var(--evc-red), var(--evc-orange));
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
            .wrap {
                grid-template-columns: 1fr;
            }
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
                    <div class="sub">Session expirée</div>
                </div>
            </div>

            <div class="code">
                <div class="n">419</div>
                <div class="label">Page expirée</div>
            </div>

            <h1>Ta session a expiré</h1>
            <p>
                Cela arrive généralement après une longue inactivité ou lorsque le navigateur a bloqué/rafraîchi un formulaire.
                Pour continuer, rafraîchis la page ou reconnecte-toi.
            </p>

            <div class="actions">
                <button class="btn primary" type="button" onclick="window.location.reload();">
                    Rafraîchir
                </button>

                <a class="btn" href="{{ url('/') }}">Accueil</a>

                <a class="btn" href="@if (\Illuminate\Support\Facades\Route::has('login')){{ route('login') }}@else{{ url('/login') }}@endif">
                    Connexion
                </a>
            </div>

            <div class="footer">
                Si le problème persiste, essaie de vider le cache du navigateur ou réessaie dans quelques minutes.
            </div>
        </section>

        <aside class="side">
            <div class="card">
                <div class="tip">
                    <div class="tip-title">Pourquoi ça arrive ?</div>
                    <p class="tip-text">
                        Cette page s’affiche quand le jeton de sécurité (CSRF) n’est plus valide.
                        Un retour en arrière, une double soumission ou une session expirée peuvent provoquer cela.
                    </p>
                </div>
                <div class="tip" style="margin-top: 12px;">
                    <div class="tip-title">Bon réflexe</div>
                    <p class="tip-text">
                        Évite d’envoyer un formulaire après avoir laissé l’onglet ouvert longtemps.
                        Rafraîchis la page avant de soumettre.
                    </p>
                </div>
            </div>
        </aside>
    </main>
</body>
</html>
