<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Évaluation des Groupes - Studio Creative 5</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #eef3ff, #ffffff, #f4edff);
            color: #090b2d;
        }

        .page {
            max-width: 1250px;
            margin: auto;
            padding: 35px 25px;
        }

        .hero {
            position: relative;
            text-align: center;
            padding: 40px 20px 60px;
            border-radius: 0 0 35px 35px;
            background: linear-gradient(120deg, #f8fbff, #ffffff, #efe6ff);
            overflow: hidden;
        }

        .logo {
            position: absolute;
            top: 35px;
            left: 35px;
            font-size: 24px;
            font-weight: 800;
            color: #140f3f;
            line-height: 1.1;
        }

        .trophy {
            position: absolute;
            right: 70px;
            top: 25px;
            font-size: 95px;
            filter: drop-shadow(0 12px 20px rgba(255, 153, 0, 0.35));
        }

        h1 {
            font-size: 48px;
            font-weight: 900;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 28px;
            color: #6428e8;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .intro {
            color: #4c5070;
            font-size: 16px;
            line-height: 1.6;
        }

        .card {
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid #dde3f3;
            border-radius: 22px;
            box-shadow: 0 18px 45px rgba(71, 55, 150, 0.08);
            padding: 25px;
            backdrop-filter: blur(12px);
        }

        .jury-info {
            margin-top: -35px;
            position: relative;
            z-index: 2;
        }

        .section-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #25284f;
        }

        .field input,
        .field select,
        textarea {
            width: 100%;
            border: 1px solid #dce2f2;
            border-radius: 13px;
            padding: 15px;
            font-size: 15px;
            outline: none;
            background: #fff;
            color: #151735;
        }

        .field input:focus,
        .field select:focus,
        textarea:focus {
            border-color: #6d35f2;
            box-shadow: 0 0 0 4px rgba(109, 53, 242, 0.1);
        }

        .groups-area {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 25px;
            margin-top: 22px;
        }

        .group-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .group-btn {
            border: 1px solid #dce2f2;
            background: #fff;
            padding: 17px;
            border-radius: 16px;
            font-weight: 800;
            color: #11143b;
            cursor: pointer;
            transition: 0.25s;
        }

        .group-btn.active,
        .group-btn:hover {
            border-color: #6d35f2;
            color: #6029e6;
            box-shadow: 0 10px 25px rgba(98, 41, 230, 0.15);
        }

        .evaluation-grid {
            margin-top: 25px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .eval-card {
            border-radius: 24px;
            padding: 25px;
            border: 1px solid #e2e6f4;
            box-shadow: 0 15px 40px rgba(44, 38, 120, 0.08);
        }

        .purple { background: linear-gradient(145deg, #ffffff, #f2ecff); }
        .green { background: linear-gradient(145deg, #ffffff, #eafff6); }
        .orange { background: linear-gradient(145deg, #ffffff, #fff3df); }
        .pink { background: linear-gradient(145deg, #ffffff, #fff0f7); }

        .eval-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 22px;
        }

        .eval-title {
            display: flex;
            gap: 15px;
            align-items: center;
            font-size: 22px;
            font-weight: 900;
            line-height: 1.15;
        }

        .icon {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            font-size: 28px;
            color: #fff;
            flex-shrink: 0;
        }

        .purple .icon { background: linear-gradient(135deg, #7b35ff, #4215bf); }
        .green .icon { background: linear-gradient(135deg, #28d891, #0ca66c); }
        .orange .icon { background: linear-gradient(135deg, #ff9c1a, #ff6500); }
        .pink .icon { background: linear-gradient(135deg, #ff4e9a, #d10065); }

        .score-pill {
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 900;
            font-size: 17px;
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(120, 90, 240, 0.2);
            white-space: nowrap;
        }

        .criteria {
            border: 1px solid #dce2f2;
            border-radius: 18px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.72);
        }

        .criterion {
            display: grid;
            grid-template-columns: 1fr 130px 80px;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid #e6eaf5;
        }

        .criterion:last-child {
            border-bottom: none;
        }

        .criterion span {
            font-size: 14px;
            font-weight: 600;
            color: #20234a;
        }

        .score-input {
            width: 100%;
            border: 1px solid #dce2f2;
            border-radius: 999px;
            padding: 10px 12px;
            text-align: center;
            font-weight: 900;
            font-size: 15px;
            outline: none;
            background: #fff;
        }

        .stars {
            display: flex;
            gap: 1px;
            font-size: 18px;
            color: #6b35f2;
            letter-spacing: 1px;
            justify-content: flex-end;
        }

        .green .stars { color: #14b979; }
        .orange .stars { color: #ff7a00; }
        .pink .stars { color: #ee1d7b; }

        .note {
            padding: 7px 12px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 14px;
            background: #fff;
            color: #5521d9;
            text-align: center;
        }

        .total {
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            font-weight: 900;
            font-size: 20px;
        }

        .comment-box {
            margin-top: 25px;
        }

        textarea {
            min-height: 115px;
            resize: vertical;
            line-height: 1.6;
        }

        .bottom {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 25px;
            align-items: center;
            margin-top: 25px;
        }

        .reminder {
            background: #f4f0ff;
            border: 1px solid #ddd2ff;
            border-radius: 20px;
            padding: 20px;
            font-weight: 700;
            color: #22125a;
        }

        .btn {
            border: none;
            border-radius: 50px;
            padding: 18px 35px;
            font-weight: 900;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-outline {
            background: #fff;
            color: #3516b8;
            border: 2px solid #6d35f2;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7b35ff, #541bd8);
            color: #fff;
            box-shadow: 0 15px 30px rgba(91, 35, 220, 0.35);
        }

        .alert {
            margin: 22px 0;
            padding: 16px 20px;
            border-radius: 16px;
            font-weight: 700;
        }

        .alert-success {
            color: #065f46;
            background: #d1fae5;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }

        .errors {
            margin: 22px 0;
            padding: 16px 20px;
            border-radius: 16px;
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
            font-weight: 700;
        }

        .errors ul {
            padding-left: 20px;
        }

        @media (max-width: 950px) {
            .form-grid,
            .evaluation-grid,
            .groups-area,
            .bottom {
                grid-template-columns: 1fr;
            }

            .group-buttons {
                grid-template-columns: repeat(2, 1fr);
            }

            h1 {
                font-size: 36px;
            }

            .trophy {
                position: static;
                margin-top: 20px;
            }

            .logo {
                position: static;
                margin-bottom: 25px;
            }
        }

        @media (max-width: 600px) {
            .criterion {
                grid-template-columns: 1fr;
            }

            .stars {
                justify-content: flex-start;
            }

            .group-buttons {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="hero">
            <div class="logo">Studio<br>Creative 5</div>

            <h1>Évaluation des Groupes</h1>
            <div class="subtitle">Membres du jury</div>

            <p class="intro">
                Notez chaque groupe selon les catégories ci-dessous.<br>
                Vos évaluations détermineront les distinctions officielles.
            </p>

            <div class="trophy">🏆</div>
        </section>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="errors">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $storeRoute }}" id="evaluationForm">
            @csrf
            <input type="hidden" name="status" id="statusInput" value="submitted">

            <section class="card jury-info">
                <h2 class="section-title">👤 Informations du jury</h2>

                <div class="form-grid">
                    <div class="field">
                        <label>Nom et prénom</label>
                        <input type="text" name="jury_name" value="{{ old('jury_name') }}" placeholder="Ex : Jean Dupont" required>
                    </div>

                    <div class="field">
                        <label>Fonction / Organisation</label>
                        <input type="text" name="jury_function" value="{{ old('jury_function') }}" placeholder="Ex : Directeur Créatif">
                    </div>

                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="jury_email" value="{{ old('jury_email') }}" placeholder="exemple@email.com" required>
                    </div>

                    <div class="field">
                        <label>Date de l’évaluation</label>
                        <input type="date" name="evaluation_date" value="{{ old('evaluation_date', now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
            </section>

            <section class="groups-area">
                <div class="card">
                    <h2 class="section-title">👥 Groupe à évaluer</h2>
                    <div class="field">
                        <select name="group_name" id="groupSelect" required>
                            @foreach($groups as $groupValue => $groupLabel)
                                <option value="{{ $groupValue }}" @selected(old('group_name', 'Groupe 1') === $groupValue)>{{ $groupLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card group-buttons">
                    @foreach($groups as $groupValue => $groupLabel)
                        <button type="button" class="group-btn" data-group="{{ $groupValue }}">👥 {{ $groupLabel }}</button>
                    @endforeach
                </div>
            </section>

            <section class="evaluation-grid">
                @foreach($categories as $categoryKey => $category)
                    <div class="eval-card {{ $category['theme'] }}" data-category="{{ $categoryKey }}">
                        <div class="eval-head">
                            <div class="eval-title">
                                <div class="icon">{{ $category['icon'] }}</div>
                                <div>{{ $category['label'] }}</div>
                            </div>
                            <div class="score-pill">/80 points</div>
                        </div>

                        <div class="criteria">
                            @foreach($category['criteria'] as $criterionKey => $criterionLabel)
                                @php
                                    $oldScore = old("scores.{$categoryKey}.{$criterionKey}", 0);
                                @endphp
                                <div class="criterion">
                                    <span>{{ $criterionLabel }}</span>
                                    <div class="stars" data-stars-for="scores[{{ $categoryKey }}][{{ $criterionKey }}]">☆☆☆☆☆</div>
                                    <div>
                                        <input
                                            type="number"
                                            class="score-input"
                                            name="scores[{{ $categoryKey }}][{{ $criterionKey }}]"
                                            value="{{ $oldScore }}"
                                            min="0"
                                            max="20"
                                            required
                                        >
                                        <div class="note">{{ $oldScore }}/20</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="total">
                            <span>Total catégorie</span>
                            <strong><span class="category-total">0</span> / 80</strong>
                        </div>
                    </div>
                @endforeach
            </section>

            <section class="card comment-box">
                <h2 class="section-title">💬 Appréciation globale sur ce groupe</h2>
                <textarea name="global_comment" placeholder="Exprimez ici votre avis général sur ce groupe, leurs forces, points d’amélioration...">{{ old('global_comment') }}</textarea>
            </section>

            <section class="bottom">
                <div class="reminder">
                    💡 Rappel important : veuillez évaluer les 4 groupes séparément pour chaque catégorie. Total actuel : <strong><span id="grandTotal">0</span> / 320</strong>
                </div>

                <button type="submit" class="btn btn-outline" data-status="draft">💾 Enregistrer brouillon</button>
                <button type="submit" class="btn btn-primary" data-status="submitted">🚀 Soumettre mon évaluation</button>
            </section>
        </form>
    </main>

    <script>
        const groupSelect = document.getElementById('groupSelect');
        const groupButtons = document.querySelectorAll('.group-btn');
        const scoreInputs = document.querySelectorAll('.score-input');
        const grandTotal = document.getElementById('grandTotal');
        const statusInput = document.getElementById('statusInput');

        function updateGroupButtons() {
            groupButtons.forEach((button) => {
                button.classList.toggle('active', button.dataset.group === groupSelect.value);
            });
        }

        function starsFromScore(score) {
            const fullStars = Math.round(Number(score || 0) / 4);
            return '★'.repeat(fullStars) + '☆'.repeat(5 - fullStars);
        }

        function updateTotals() {
            let total = 0;

            document.querySelectorAll('.eval-card').forEach((card) => {
                let categoryTotal = 0;

                card.querySelectorAll('.score-input').forEach((input) => {
                    let value = Number(input.value || 0);
                    value = Math.max(0, Math.min(20, value));
                    input.value = value;
                    categoryTotal += value;

                    const note = input.parentElement.querySelector('.note');
                    const stars = input.closest('.criterion').querySelector('.stars');
                    note.textContent = value + '/20';
                    stars.textContent = starsFromScore(value);
                });

                card.querySelector('.category-total').textContent = categoryTotal;
                total += categoryTotal;
            });

            grandTotal.textContent = total;
        }

        groupButtons.forEach((button) => {
            button.addEventListener('click', () => {
                groupSelect.value = button.dataset.group;
                updateGroupButtons();
            });
        });

        groupSelect.addEventListener('change', updateGroupButtons);
        scoreInputs.forEach((input) => input.addEventListener('input', updateTotals));

        document.querySelectorAll('[data-status]').forEach((button) => {
            button.addEventListener('click', () => {
                statusInput.value = button.dataset.status;
            });
        });

        updateGroupButtons();
        updateTotals();
    </script>
</body>
</html>
