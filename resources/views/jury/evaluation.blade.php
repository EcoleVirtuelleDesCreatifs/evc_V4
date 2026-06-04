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
            min-height: -webkit-fill-available;
            padding: 110px 20px 40px;
            background: var(--jury-bg-dark);
            color: var(--jury-text-primary);
        }

        .hero {
            position: relative;
            text-align: center;
            max-width: 900px;
            margin: 0 auto 24px;
            padding: 16px 20px 0;
            background: transparent;
            overflow: hidden;
        }

        @media (max-width: 1024px) {
            .page {
                padding-top: 120px;
            }
        }

        @media (max-width: 768px) {
            .page {
                padding-top: 100px;
            }

            .hero {
                padding-top: 8px;
            }
        }

        @media (max-width: 640px) {
            .page {
                padding-top: 90px;
            }
        }

        @media (max-width: 400px) {
            .page {
                padding-top: 80px;
            }
        }

        .logo {
            position: static;
            display: inline-block;
            padding: 4px 12px;
            margin-bottom: 10px;
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
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--jury-primary) 0%, var(--jury-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 14px;
            color: var(--jury-text-secondary);
            font-weight: 400;
            margin-bottom: 8px;
        }

        .intro {
            max-width: 620px;
            margin: 0 auto;
            color: var(--jury-text-secondary);
            font-size: 14px;
            line-height: 1.5;
        }

        .card {
            max-width: 1180px;
            margin-left: auto;
            margin-right: auto;
            background: var(--jury-bg-card);
            border: 1px solid var(--jury-border);
            border-radius: 12px;
            box-shadow: none;
            padding: 18px 24px;
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
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 14px;
            display: flex;
            gap: 8px;
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
            font-size: 16px; /* ≥16px évite le zoom automatique sur iOS/Safari */
            outline: none;
            background: var(--jury-bg-dark);
            color: var(--jury-text-primary);
            transition: all 0.2s;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .field input:focus,
        .field select:focus,
        textarea:focus {
            border-color: var(--jury-primary);
            box-shadow: none;
        }

        .groups-area {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 16px;
            max-width: 1180px;
            margin: 24px auto 0;
        }

        .group-buttons {
            display: flex;
            flex-wrap: wrap;
            align-content: flex-start;
            gap: 10px;
        }

        .group-btn {
            border: 1px solid var(--jury-border);
            background: var(--jury-bg-dark);
            padding: 8px 18px;
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
            margin: 16px auto 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .eval-card {
            border-radius: 12px;
            padding: 18px 24px;
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
            gap: 10px;
            margin-bottom: 12px;
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
            font-size: 16px; /* évite zoom iOS */
            outline: none;
            background: var(--jury-bg-card);
            color: var(--jury-text-primary);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Masquer les flèches natifs input number */
        .score-input::-webkit-inner-spin-button,
        .score-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .score-input[type=number] {
            -moz-appearance: textfield;
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
            gap: 16px;
            align-items: center;
            max-width: 1180px;
            margin: 16px auto 0;
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
                flex-direction: row;
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
                padding: 90px 12px 36px;
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
                flex-direction: row;
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
                padding-top: 80px;
                padding-left: 10px;
                padding-right: 10px;
            }

            h1 {
                font-size: 26px;
            }

            .group-btn {
                padding: 7px 14px;
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
                    <input type="text" id="jury_identifier_input" placeholder="Ex : JURY-2024-XYZ" autocomplete="off"
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
                            ✅ Vous avez déjà noté tous les groupes. Merci pour votre participation.
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

                            @if (!empty($category['brief']))
                                <div
                                    style="background:rgba(255,255,255,.04);border-left:3px solid var(--jury-primary);border-radius:0 8px 8px 0;padding:.6rem .9rem;margin-bottom:1rem;font-size:.78rem;color:#94a3b8;line-height:1.5;">
                                    📋 {{ $category['brief'] }}
                                </div>
                            @endif

                            <div class="criteria">
                                @foreach ($category['criteria'] as $criterionKey => $criterionLabel)
                                    @php
                                        $oldScore = old("scores.{$categoryKey}.{$criterionKey}", '');
                                    @endphp
                                    <div class="criterion">
                                        <span>{{ $criterionLabel }}</span>
                                        <div class="stars"
                                            data-stars-for="scores[{{ $categoryKey }}][{{ $criterionKey }}]">
                                            ☆☆☆☆☆</div>
                                        <div>
                                            <input type="number" inputmode="numeric" pattern="[0-9]*" class="score-input"
                                                name="scores[{{ $categoryKey }}][{{ $criterionKey }}]"
                                                value="{{ $oldScore }}" min="0" max="20" placeholder="0" required>
                                            <div class="note">{{ $oldScore !== '' ? $oldScore : 0 }}/20</div>
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
                        💡 Rappel important : les groupes déjà notés avec votre identifiant disparaissent automatiquement. Vous devez noter tous les groupes disponibles. Total actuel
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
        const LOOKUP_URL = window.location.pathname.startsWith('/evc/') ?
            '/evc/jury/evaluation/lookup' :
            '/jury/evaluation/lookup';

        const identifierInput = document.getElementById('jury_identifier_input');
        const lookupBtn = document.getElementById('juryLookupBtn');
        const juryFeedback = document.getElementById('juryFeedback');
        const evaluationBody = document.getElementById('evaluationBody');
        const juryLoginSection = document.getElementById('juryLoginSection');
        const juryNameDisplay = document.getElementById('juryNameDisplay');
        const juryIdentHidden = document.getElementById('juryIdentifierHidden');
        const groupSelect = document.getElementById('groupSelect');
        const groupBtnContainer = document.getElementById('groupButtonsContainer');
        const allGroupKeys = @json(array_keys($groups));

        function showFeedback(msg, ok) {
            juryFeedback.style.display = 'block';
            juryFeedback.style.color = ok ? '#22c55e' : '#ef4444';
            juryFeedback.innerHTML = msg;
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
                        groupBtnContainer.querySelectorAll('.group-btn').forEach(b => b.classList.toggle(
                            'active', b.dataset.group === g));
                    });
                    groupBtnContainer.appendChild(btn);
                }
            });
        }

        async function doLookup() {
            const val = identifierInput ? identifierInput.value.trim() : '';
            if (!val) {
                showFeedback('Veuillez saisir votre identifiant.', false);
                return;
            }

            lookupBtn.disabled = true;
            lookupBtn.textContent = 'Vérification...';

            try {
                const res = await fetch(LOOKUP_URL + '?jury_identifier=' + encodeURIComponent(val), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                let data;
                try {
                    data = await res.json();
                } catch (jsonErr) {
                    showFeedback('❌ Erreur serveur (réponse invalide). Contactez l\'administrateur.', false);
                    lookupBtn.disabled = false;
                    lookupBtn.textContent = 'Valider';
                    return;
                }

                if (!res.ok) {
                    showFeedback('❌ Erreur serveur (' + res.status + ')' + (data.debug ? ' : ' + data.debug : '') + '.',
                        false);
                    evaluationBody.style.display = 'none';
                } else if (!data.found) {
                    const msg = data.debug ?
                        '❌ Erreur : ' + data.debug :
                        '❌ Identifiant non reconnu. Vérifiez votre identifiant unique.';
                    showFeedback(msg, false);
                    evaluationBody.style.display = 'none';
                } else if (data.already_voted) {
                    showFeedback(
                        '✅ Bienvenue, ' + data.name + '. Vous avez déjà noté tous les groupes. Merci pour votre participation !',
                        true
                    );
                    evaluationBody.style.display = 'none';
                    const isEvc = window.location.pathname.startsWith('/evc/');
                    const resultsUrl = (isEvc ? '/evc/jury/resultats/' : '/jury/resultats/') + data.id;
                    let resultBtn = document.getElementById('resultsBtn');
                    if (!resultBtn) {
                        resultBtn = document.createElement('div');
                        resultBtn.id = 'resultsBtn';
                        resultBtn.style.cssText = 'text-align:center;margin-top:1.25rem;';
                        juryFeedback.parentNode.insertBefore(resultBtn, juryFeedback.nextSibling);
                    }
                    resultBtn.innerHTML = '<a href="' + resultsUrl + '" style="display:inline-flex;align-items:center;gap:.6rem;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;font-weight:700;font-size:1rem;border:none;border-radius:10px;padding:.8rem 1.8rem;text-decoration:none;box-shadow:0 4px 15px rgba(245,158,11,.3);">🏆 Voir mes résultats</a>';
                } else {
                    const progress = data.evaluated_count > 0
                        ? ' Vous avez déjà noté ' + data.evaluated_count + '/' + data.total_groups + ' groupe(s).'
                        : '';
                    showFeedback('✅ Bienvenue, ' + data.name + ' !' + progress, true);
                    juryNameDisplay.textContent = data.name + (data.title ? ' — ' + data.title : '');
                    juryIdentHidden.value = val;
                    buildGroupUI(data.available_groups);
                    evaluationBody.style.display = 'block';
                    updateTotals();
                }
            } catch (e) {
                showFeedback('❌ Erreur réseau : ' + e.message, false);
            }

            lookupBtn.disabled = false;
            lookupBtn.textContent = 'Valider';
        }

        if (lookupBtn) {
            lookupBtn.addEventListener('click', doLookup);
            lookupBtn.addEventListener('touchend', function(e) {
                e.preventDefault();
                doLookup();
            });
        }
        if (identifierInput) identifierInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                doLookup();
            }
        });

        @if (old('jury_identifier'))
            document.addEventListener('DOMContentLoaded', () => doLookup());
        @endif

        const scoreInputs = document.querySelectorAll('.score-input');
        const grandTotal = document.getElementById('grandTotal');
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
                        const raw = input.value.trim();
                        let value = raw === '' ? 0 : Math.max(0, Math.min(20, Number(raw)));
                        if (raw !== '' && Number(raw) !== value) input.value = value;
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

            /* Refresh CSRF token avant soumission (fix Safari ITP / session expirée) */
            const evalForm = document.getElementById('evaluationForm');
            if (evalForm) {
                evalForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    try {
                        const csrfRoute = window.location.pathname.startsWith('/evc/') ? '/csrf-token' : '/csrf-token';
                        const res = await fetch(csrfRoute, { credentials: 'same-origin' });
                        if (res.ok) {
                            const json = await res.json();
                            const csrfInput = evalForm.querySelector('input[name="_token"]');
                            if (csrfInput && json.token) csrfInput.value = json.token;
                        }
                    } catch (_) {}
                    evalForm.submit();
                });
            }

            updateTotals();
        }
    </script>
@endpush
