@extends('layouts.app')

@section('title', 'Évaluation des groupes - Membres du Jury | EVC')
@section('description', 'Page officielle d’évaluation des groupes Studio Créatif par les membres du jury EVC.')
@section('keywords', 'jury EVC, évaluation groupes, studio créatif, école virtuelle des créatifs')

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

        .page {
            min-height: 100vh;
            padding: 160px 20px 60px;
            background: var(--jury-bg-dark);
            color: var(--jury-text-primary);
        }

        .hero {
            position: relative;
            text-align: center;
            max-width: 900px;
            margin: 0 auto 60px;
            padding: 60px 20px 0;
            background: transparent;
            overflow: hidden;
        }

        .logo {
            position: static;
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
            line-height: 1.2;
            letter-spacing: 0.5px;
        }

        .trophy {
            display: none;
        }

        h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -1px;
            background: linear-gradient(135deg, var(--jury-primary) 0%, var(--jury-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 18px;
            color: var(--jury-text-secondary);
            font-weight: 400;
            margin-bottom: 20px;
        }

        .intro {
            max-width: 620px;
            margin: 0 auto;
            color: var(--jury-text-secondary);
            font-size: 18px;
            line-height: 1.6;
        }

        .card {
            max-width: 1180px;
            margin-left: auto;
            margin-right: auto;
            background: var(--jury-bg-card);
            border: 1px solid var(--jury-border);
            border-radius: 12px;
            box-shadow: none;
            padding: 32px;
            backdrop-filter: none;
            transition: all 0.3s;
        }

        .card:hover,
        .eval-card:hover {
            border-color: var(--jury-primary);
        }

        .jury-info {
            margin-top: 0;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
            display: flex;
            gap: 10px;
            align-items: center;
            color: var(--jury-text-primary);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .field label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--jury-text-primary);
        }

        .field input,
        .field select,
        textarea {
            width: 100%;
            border: 1px solid var(--jury-border);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            outline: none;
            background: var(--jury-bg-dark);
            color: var(--jury-text-primary);
            transition: all 0.2s;
        }

        .field input:focus,
        .field select:focus,
        textarea:focus {
            border-color: var(--jury-primary);
            box-shadow: none;
        }

        .groups-area {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 25px;
            max-width: 1180px;
            margin: 24px auto 0;
        }

        .group-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .group-btn {
            border: 1px solid var(--jury-border);
            background: var(--jury-bg-dark);
            padding: 18px;
            border-radius: 12px;
            font-weight: 600;
            color: var(--jury-text-primary);
            cursor: pointer;
            transition: all 0.2s;
        }

        .group-btn.active,
        .group-btn:hover {
            border-color: var(--jury-primary);
            color: var(--jury-primary);
            background: rgba(255, 152, 0, 0.08);
            box-shadow: none;
        }

        .evaluation-grid {
            max-width: 1180px;
            margin: 40px auto 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .eval-card {
            border-radius: 12px;
            padding: 32px;
            border: 1px solid var(--jury-border);
            box-shadow: none;
            transition: all 0.3s;
        }

        .purple,
        .green,
        .orange,
        .pink {
            background: var(--jury-bg-card);
        }

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
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 28px;
            color: #fff;
            flex-shrink: 0;
        }

        .purple .icon,
        .green .icon,
        .orange .icon,
        .pink .icon {
            background: var(--jury-gradient);
        }

        .score-pill {
            padding: 8px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 14px;
            background: rgba(255, 152, 0, 0.1);
            border: 1px solid rgba(255, 152, 0, 0.3);
            color: var(--jury-primary);
            white-space: nowrap;
        }

        .criteria {
            border: 1px solid var(--jury-border);
            border-radius: 8px;
            overflow: hidden;
            background: var(--jury-bg-dark);
        }

        .criterion {
            display: grid;
            grid-template-columns: 1fr 130px 80px;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid var(--jury-border);
        }

        .criterion:last-child {
            border-bottom: none;
        }

        .criterion span {
            font-size: 14px;
            font-weight: 600;
            color: var(--jury-text-primary);
        }

        .score-input {
            width: 100%;
            border: 1px solid var(--jury-border);
            border-radius: 8px;
            padding: 10px 12px;
            text-align: center;
            font-weight: 900;
            font-size: 15px;
            outline: none;
            background: var(--jury-bg-card);
            color: var(--jury-text-primary);
        }

        .stars {
            display: flex;
            gap: 1px;
            font-size: 18px;
            color: var(--jury-primary);
            letter-spacing: 1px;
            justify-content: flex-end;
        }

        .green .stars,
        .orange .stars,
        .pink .stars {
            color: var(--jury-primary);
        }

        .note {
            padding: 7px 12px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 14px;
            background: rgba(255, 152, 0, 0.1);
            color: var(--jury-primary);
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
            margin-top: 40px;
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
            max-width: 1180px;
            margin: 32px auto 0;
        }

        .reminder {
            background: rgba(255, 152, 0, 0.1);
            border: 1px solid rgba(255, 152, 0, 0.3);
            border-radius: 8px;
            padding: 16px;
            font-weight: 500;
            color: var(--jury-text-primary);
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 14px 24px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-outline {
            background: transparent;
            color: var(--jury-primary);
            border: 1px solid var(--jury-primary);
        }

        .btn-primary {
            background: var(--jury-primary);
            color: #fff;
            box-shadow: none;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary:hover {
            background: var(--jury-primary-dark);
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

        .empty-state {
            max-width: 760px;
            margin: 0 auto;
            text-align: center;
        }

        .empty-state .icon {
            margin: 0 auto 20px;
        }

        .empty-state p {
            color: var(--jury-text-secondary);
            font-size: 17px;
            line-height: 1.7;
            margin: 0;
        }

        @media (max-width: 950px) {
            .page {
                padding: 130px 16px 44px;
            }

            .hero {
                padding: 40px 0 0;
                margin-bottom: 40px;
            }

            .form-grid,
            .evaluation-grid,
            .groups-area,
            .bottom {
                grid-template-columns: 1fr;
            }

            .card,
            .eval-card {
                padding: 24px;
            }

            .group-buttons {
                grid-template-columns: repeat(2, 1fr);
            }

            .eval-head {
                align-items: flex-start;
            }

            .bottom {
                gap: 16px;
            }

            .btn {
                width: 100%;
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
            .page {
                padding: 115px 12px 36px;
            }

            .hero {
                margin-bottom: 28px;
            }

            .logo {
                font-size: 12px;
                padding: 6px 12px;
            }

            h1 {
                font-size: 30px;
                line-height: 1.1;
            }

            .subtitle {
                font-size: 16px;
            }

            .intro {
                font-size: 15px;
            }

            .card,
            .eval-card {
                padding: 18px;
                border-radius: 10px;
            }

            .section-title {
                font-size: 20px;
                align-items: flex-start;
            }

            .eval-head {
                flex-direction: column;
            }

            .eval-title {
                font-size: 19px;
                align-items: flex-start;
            }

            .icon {
                width: 46px;
                height: 46px;
                font-size: 24px;
            }

            .score-pill {
                align-self: flex-start;
            }

            .criterion {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .stars {
                justify-content: flex-start;
            }

            .group-buttons {
                grid-template-columns: 1fr;
            }

            .total {
                font-size: 17px;
                gap: 12px;
            }

            .reminder {
                font-size: 14px;
            }

            .btn {
                padding: 13px 18px;
                font-size: 14px;
            }
        }

        @media (max-width: 380px) {
            .page {
                padding-left: 10px;
                padding-right: 10px;
            }

            h1 {
                font-size: 26px;
            }

            .group-btn {
                padding: 14px;
                font-size: 14px;
            }

            .eval-title {
                font-size: 17px;
            }
        }
    </style>

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

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Étape 1 : saisie identifiant --}}
            <section class="card jury-info" id="juryLoginSection">
                <h2 class="section-title">👤 Identification du jury</h2>
                <div class="form-grid">
                    <div class="field">
                        <label for="jury_identifier_input">Votre identifiant unique</label>
                        <input type="text" id="jury_identifier_input"
                            placeholder="Ex : JURY-2024-XYZ"
                            autocomplete="off"
                            value="{{ old('jury_identifier') }}">
                    </div>
                    <div class="field" style="display:flex;align-items:flex-end;">
                        <button type="button" id="juryLookupBtn" class="btn btn-primary" style="width:100%;">Valider</button>
                    </div>
                </div>
                <div id="juryFeedback" class="mt-2" style="display:none;"></div>
            </section>

        {{-- Étape 2 : formulaire complet (caché jusqu'à validation) --}}
        @php $oldIdentifier = old('jury_identifier'); @endphp
        <div id="evaluationBody" style="display:none;">
            <form method="POST" action="{{ $storeRoute }}" id="evaluationForm">
                @csrf
                <input type="hidden" name="status" id="statusInput" value="submitted">
                <input type="hidden" name="jury_identifier" id="juryIdentifierHidden" value="{{ $oldIdentifier }}">

                <section class="card jury-info">
                    <h2 class="section-title">👤 Informations du jury</h2>
                    <div class="form-grid">
                        <div class="field">
                            <label>Membre identifié</label>
                            <div id="juryNameDisplay" style="font-weight:600;font-size:1.1rem;padding:0.5rem 0;"></div>
                        </div>
                        <input type="hidden" name="evaluation_date"
                            value="{{ old('evaluation_date', now()->format('Y-m-d')) }}">
                    </div>
                </section>

                <section class="groups-area">
                    <div class="card">
                        <h2 class="section-title">👥 Groupe à évaluer</h2>
                        <div class="field">
                            <select name="group_name" id="groupSelect" required></select>
                        </div>
                        <div id="allEvaluatedMsg" style="display:none;color:#f97316;font-weight:600;margin-top:0.5rem;">
                            ✅ Vous avez déjà noté tous les groupes.
                        </div>
                    </div>
                    <div class="card group-buttons" id="groupButtonsContainer"></div>
                </section>

                <section class="evaluation-grid">
                    @foreach ($categories as $categoryKey => $category)
                        <div class="eval-card {{ $category['theme'] }}" data-category="{{ $categoryKey }}">
                            <div class="eval-head">
                                <div class="eval-title">
                                    <div class="icon">{{ $category['icon'] }}</div>
                                    <div>{{ $category['label'] }}</div>
                                </div>
                                <div class="score-pill">/80 points</div>
                            </div>

                            <div class="criteria">
                                @foreach ($category['criteria'] as $criterionKey => $criterionLabel)
                                    @php
                                        $oldScore = old("scores.{$categoryKey}.{$criterionKey}", 0);
                                    @endphp
                                    <div class="criterion">
                                        <span>{{ $criterionLabel }}</span>
                                        <div class="stars"
                                            data-stars-for="scores[{{ $categoryKey }}][{{ $criterionKey }}]">
                                            ☆☆☆☆☆</div>
                                        <div>
                                            <input type="number" class="score-input"
                                                name="scores[{{ $categoryKey }}][{{ $criterionKey }}]"
                                                value="{{ $oldScore }}" min="0" max="20" required>
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
                    <textarea name="global_comment"
                        placeholder="Exprimez ici votre avis général sur ce groupe, leurs forces, points d’amélioration...">{{ old('global_comment') }}</textarea>
                </section>

                <section class="bottom">
                    <div class="reminder">
                        💡 Rappel important : veuillez évaluer les 4 groupes séparément pour chaque catégorie. Total actuel
                        :
                        <strong><span id="grandTotal">0</span> / 320</strong>
                    </div>

                    <button type="submit" class="btn btn-outline" data-status="draft">💾 Enregistrer brouillon</button>
                    <button type="submit" class="btn btn-primary" data-status="submitted">🚀 Soumettre mon
                        évaluation</button>
                </section>
            </form>
        </div>{{-- #evaluationBody --}}
    </main>
@endsection

@push('scripts')
    <script>
        const LOOKUP_URL = window.location.pathname.startsWith('/evc/')
            ? '/evc/jury/evaluation/lookup'
            : '/jury/evaluation/lookup';

        const identifierInput  = document.getElementById('jury_identifier_input');
        const lookupBtn        = document.getElementById('juryLookupBtn');
        const juryFeedback     = document.getElementById('juryFeedback');
        const evaluationBody   = document.getElementById('evaluationBody');
        const juryLoginSection = document.getElementById('juryLoginSection');
        const juryNameDisplay  = document.getElementById('juryNameDisplay');
        const juryIdentHidden  = document.getElementById('juryIdentifierHidden');
        const groupSelect      = document.getElementById('groupSelect');
        const groupBtnContainer= document.getElementById('groupButtonsContainer');
        const allGroupKeys     = @json(array_keys($groups));

        function showFeedback(msg, ok) {
            juryFeedback.style.display = 'block';
            juryFeedback.style.color   = ok ? '#22c55e' : '#ef4444';
            juryFeedback.textContent   = msg;
        }

        function buildGroupUI(availableGroups) {
            groupSelect.innerHTML = '';
            if (groupBtnContainer) groupBtnContainer.innerHTML = '';
            const allEvaluatedMsg = document.getElementById('allEvaluatedMsg');

            if (availableGroups.length === 0) {
                if (allEvaluatedMsg) allEvaluatedMsg.style.display = 'block';
                document.querySelectorAll('[data-status]').forEach(b => b.disabled = true);
                return;
            }
            if (allEvaluatedMsg) allEvaluatedMsg.style.display = 'none';

            availableGroups.forEach((g, i) => {
                const opt = document.createElement('option');
                opt.value = g;
                opt.textContent = g;
                if (i === 0) opt.selected = true;
                groupSelect.appendChild(opt);

                if (groupBtnContainer) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'group-btn' + (i === 0 ? ' active' : '');
                    btn.dataset.group = g;
                    btn.textContent = '👥 ' + g;
                    btn.addEventListener('click', () => {
                        groupSelect.value = g;
                        groupBtnContainer.querySelectorAll('.group-btn').forEach(b => b.classList.toggle('active', b.dataset.group === g));
                    });
                    groupBtnContainer.appendChild(btn);
                }
            });
        }

        async function doLookup() {
            const val = identifierInput ? identifierInput.value.trim() : '';
            if (!val) { showFeedback('Veuillez saisir votre identifiant.', false); return; }

            lookupBtn.disabled = true;
            lookupBtn.textContent = 'Vérification...';

            try {
                const res  = await fetch(LOOKUP_URL + '?jury_identifier=' + encodeURIComponent(val));
                const data = await res.json();

                if (!data.found) {
                    showFeedback('❌ Identifiant non reconnu. Vérifiez votre identifiant unique.', false);
                    evaluationBody.style.display = 'none';
                } else {
                    showFeedback('✅ Bienvenue, ' + data.name + ' !', true);
                    juryNameDisplay.textContent = data.name + (data.title ? ' — ' + data.title : '');
                    juryIdentHidden.value = val;
                    buildGroupUI(data.available_groups);
                    evaluationBody.style.display = 'block';
                    updateTotals();
                }
            } catch(e) {
                showFeedback('Erreur réseau. Réessayez.', false);
            }

            lookupBtn.disabled = false;
            lookupBtn.textContent = 'Valider';
        }

        if (lookupBtn) lookupBtn.addEventListener('click', doLookup);
        if (identifierInput) identifierInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); doLookup(); } });

        @if(old('jury_identifier'))
        document.addEventListener('DOMContentLoaded', () => doLookup());
        @endif

        const scoreInputs = document.querySelectorAll('.score-input');
        const grandTotal  = document.getElementById('grandTotal');
        const statusInput = document.getElementById('statusInput');

        if (grandTotal && statusInput) {

            function updateGroupButtons() {
                if (!groupBtnContainer) return;
                groupBtnContainer.querySelectorAll('.group-btn').forEach(b => {
                    b.classList.toggle('active', b.dataset.group === groupSelect.value);
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

            if (groupSelect) {
                groupSelect.addEventListener('change', updateGroupButtons);
            }
            scoreInputs.forEach((input) => input.addEventListener('input', updateTotals));

            document.querySelectorAll('[data-status]').forEach((button) => {
                button.addEventListener('click', () => {
                    statusInput.value = button.dataset.status;
                });
            });

            updateTotals();
        }
    </script>
@endpush
