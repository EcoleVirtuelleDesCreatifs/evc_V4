<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluation envoyée - Studio Creative 5</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #eef3ff, #ffffff, #f4edff);
            color: #090b2d;
            padding: 25px;
        }

        .card {
            width: min(720px, 100%);
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #dde3f3;
            border-radius: 28px;
            box-shadow: 0 24px 70px rgba(71, 55, 150, 0.12);
            padding: 55px 35px;
            backdrop-filter: blur(12px);
        }

        .icon {
            width: 92px;
            height: 92px;
            display: grid;
            place-items: center;
            margin: 0 auto 25px;
            border-radius: 28px;
            font-size: 48px;
            background: linear-gradient(135deg, #7b35ff, #541bd8);
            box-shadow: 0 15px 30px rgba(91, 35, 220, 0.35);
        }

        h1 {
            font-size: 42px;
            font-weight: 900;
            margin-bottom: 15px;
        }

        p {
            color: #4c5070;
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
            border-radius: 50px;
            padding: 16px 28px;
            font-weight: 900;
            font-size: 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7b35ff, #541bd8);
            color: #fff;
            box-shadow: 0 15px 30px rgba(91, 35, 220, 0.25);
        }

        .btn-outline {
            background: #fff;
            color: #3516b8;
            border: 2px solid #6d35f2;
        }
    </style>
</head>
<body>
    <section class="card">
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
</body>
</html>
