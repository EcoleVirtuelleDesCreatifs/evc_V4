@extends('layouts.app')

@section('title', 'Évaluation envoyée - Jury EVC')
@section('description', 'Confirmation d’envoi de l’évaluation jury Studio Créatif EVC.')
@section('keywords', 'jury EVC, évaluation envoyée, studio créatif')

@section('content')
    <style>
        :root {
            --jury-primary: #ff9800;
            --jury-primary-dark: #f57c00;
            --jury-gradient: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);
            --jury-accent: #ffb74d;
            --jury-bg-dark: #0f172a;
            --jury-bg-card: #1e293b;
            --jury-text-primary: #f1f5f9;
            --jury-text-secondary: #94a3b8;
            --jury-border: #334155;
            --jury-glow: rgba(255, 152, 0, 0.3);
        }

        .thank-page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: var(--jury-bg-dark);
            color: var(--jury-text-primary);
            padding: 160px 20px 60px;
        }

        .thank-card {
            width: min(720px, 100%);
            text-align: center;
            background: var(--jury-bg-card);
            border: 1px solid var(--jury-border);
            border-radius: 12px;
            padding: 55px 35px;
            transition: all 0.3s;
        }

        .thank-card:hover {
            border-color: var(--jury-primary);
        }

        .icon {
            width: 80px;
            height: 80px;
            display: grid;
            place-items: center;
            margin: 0 auto 25px;
            border-radius: 12px;
            font-size: 42px;
            background: var(--jury-gradient);
            box-shadow: 0 0 24px var(--jury-glow);
        }

        .header-badge {
            display: inline-block;
            padding: 6px 16px;
            margin-bottom: 20px;
            border-radius: 20px;
            border: 1px solid rgba(255, 152, 0, 0.3);
            background: rgba(255, 152, 0, 0.1);
            color: var(--jury-primary);
            box-shadow: 0 0 20px var(--jury-glow);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -1px;
            background: linear-gradient(135deg, var(--jury-primary) 0%, var(--jury-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        p {
            color: var(--jury-text-secondary);
            font-size: 17px;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border-radius: 8px;
            padding: 14px 24px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--jury-primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--jury-primary-dark);
        }

        .btn-outline {
            background: transparent;
            color: var(--jury-primary);
            border: 1px solid var(--jury-primary);
        }

        @media (max-width: 600px) {
            .thank-page {
                padding: 120px 14px 44px;
            }

            .thank-card {
                padding: 34px 20px;
            }

            h1 {
                font-size: 30px;
                line-height: 1.1;
            }

            p {
                font-size: 15px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>

    <main class="thank-page">
    <section class="thank-card">
        <span class="header-badge">ÉVALUATION JURY</span>
        <div class="icon">🏆</div>
        <h1>Merci pour votre évaluation</h1>
        <p>
            Votre notation a bien été enregistrée. Vous pouvez maintenant revenir au formulaire pour noter un autre groupe ou retourner à la page des membres du jury.
        </p>
        <div class="actions">
            <a href="{{ route('jury.evaluation.create') }}" class="btn btn-primary">Noter un autre groupe</a>
            <a href="{{ route('jury') }}" class="btn btn-outline">Voir les membres du jury</a>
        </div>
    </section>
    </main>
@endsection
