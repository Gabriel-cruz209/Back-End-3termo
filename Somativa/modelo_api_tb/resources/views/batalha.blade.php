<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modo Batalha Pokemon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #182038;
            --cream: #f8efd5;
            --cream-dark: #d9c58f;
            --green: #5dbb63;
            --yellow: #f4d35e;
            --red: #e63946;
            --blue: #5271c4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            font-family: "Press Start 2P", monospace;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.35), rgba(255, 255, 255, 0)),
                repeating-linear-gradient(0deg, #8fcf74 0 8px, #7fc768 8px 16px);
        }

        button,
        input,
        select {
            font: inherit;
        }

        .page {
            width: min(1180px, calc(100% - 24px));
            margin: 0 auto;
            padding: 24px 0 36px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .title {
            margin: 0;
            font-size: clamp(18px, 3vw, 30px);
            line-height: 1.35;
            text-shadow: 3px 3px 0 #fff;
        }

        .pixel-panel {
            border: 4px solid var(--ink);
            border-radius: 4px;
            background: var(--cream);
            box-shadow: 6px 6px 0 rgba(24, 32, 56, 0.24);
        }

        .pixel-btn {
            cursor: pointer;
            border: 3px solid var(--ink);
            border-radius: 4px;
            background: #fff8df;
            color: var(--ink);
            padding: 12px 14px;
            text-decoration: none;
            box-shadow: 4px 4px 0 rgba(24, 32, 56, 0.24);
            transition: transform 120ms ease, box-shadow 120ms ease, background 120ms ease;
        }

        .pixel-btn:hover:not(:disabled) {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 rgba(24, 32, 56, 0.24);
            background: #fff0b8;
        }

        .pixel-btn:disabled {
            cursor: not-allowed;
            opacity: 0.55;
        }

        .pixel-btn.primary {
            background: #ffcb05;
        }

        .selection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 14px;
            max-height: 62vh;
            overflow: auto;
            padding: 16px;
        }

        .battle-search-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-bottom: 3px solid var(--ink);
            background: #fff8df;
        }

        .battle-search-input {
            flex: 1;
            min-width: 0;
            border: 3px solid var(--ink);
            border-radius: 4px;
            background: #fffdf0;
            color: var(--ink);
            padding: 7px 10px;
            font-size: 10px;
            outline: none;
            box-shadow: 3px 3px 0 rgba(24,32,56,0.18);
            transition: border-color 120ms ease, box-shadow 120ms ease;
        }

        .battle-search-input:focus {
            border-color: #e63946;
            box-shadow: 3px 3px 0 rgba(230,57,70,0.22);
        }

        .battle-search-clear {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            border: 2px solid var(--ink);
            border-radius: 4px;
            background: #fff0b8;
            color: var(--ink);
            font-size: 14px;
            line-height: 1;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            transition: background 120ms ease;
        }

        .battle-search-clear:hover {
            background: #ffcb05;
        }

        .battle-search-count {
            flex-shrink: 0;
            font-size: 8px;
            color: #5f5a4b;
            white-space: nowrap;
        }

        .select-card.is-hidden {
            display: none;
        }

        .selection-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 330px;
            gap: 18px;
            align-items: start;
        }

        .select-card {
            position: relative;
            min-height: 190px;
            padding: 12px;
            text-align: center;
            border: 3px solid #665b36;
            background: #fffdf0;
            cursor: pointer;
        }

        .select-card.is-selected {
            border-color: #e63946;
            outline: 4px solid #ffcb05;
            background: #fff3bb;
        }

        .select-card img {
            width: 88px;
            height: 88px;
            object-fit: contain;
            image-rendering: pixelated;
        }

        .select-name {
            display: block;
            margin-top: 10px;
            font-size: 10px;
            line-height: 1.5;
        }

        .select-meta {
            display: block;
            margin-top: 8px;
            font-size: 8px;
            line-height: 1.6;
            color: #5f5a4b;
        }

        .team-panel {
            position: sticky;
            top: 14px;
            padding: 16px;
        }

        .slot-bar {
            height: 18px;
            border: 3px solid var(--ink);
            background: #fff;
            margin: 12px 0;
        }

        .slot-bar-fill {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #e63946, #ffcb05);
            transition: width 180ms ease;
        }

        .selected-list {
            display: grid;
            gap: 10px;
            min-height: 120px;
        }

        .selected-item {
            display: grid;
            grid-template-columns: 48px 1fr;
            gap: 10px;
            align-items: center;
            padding: 8px;
            border: 2px solid #665b36;
            background: #fffdf0;
            font-size: 9px;
            line-height: 1.5;
        }

        .selected-item img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            image-rendering: pixelated;
        }

        .battle-shell {
            width: min(920px, 100%);
            margin: 0 auto;
        }

        .battle-scene {
            position: relative;
            height: 520px;
            overflow: hidden;
            background:
                radial-gradient(ellipse at 25% 72%, rgba(120, 180, 97, 0.9) 0 18%, transparent 19%),
                radial-gradient(ellipse at 74% 29%, rgba(159, 188, 105, 0.9) 0 15%, transparent 16%),
                linear-gradient(180deg, #d8f3ff 0 42%, #f8efd5 43% 100%);
        }

        .battle-scene::before {
            content: "";
            position: absolute;
            inset: auto -40px 120px -40px;
            height: 160px;
            background: repeating-linear-gradient(0deg, #95cd74 0 8px, #7ec965 8px 16px);
            transform: skewY(-3deg);
        }

        .enemy-box,
        .player-box {
            position: absolute;
            width: min(360px, 48%);
            padding: 14px;
            border: 4px solid var(--ink);
            background: #fffdf0;
            z-index: 3;
        }

        .enemy-box {
            left: 28px;
            top: 30px;
        }

        .player-box {
            right: 28px;
            bottom: 112px;
        }

        .battle-name {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
            line-height: 1.6;
        }

        .hp-label {
            margin-top: 8px;
            font-size: 9px;
        }

        .hp-shell {
            height: 14px;
            border: 3px solid var(--ink);
            background: #fff;
            margin-top: 6px;
        }

        .hp-fill {
            height: 100%;
            width: 100%;
            background: var(--green);
            transition: width 320ms ease, background 160ms ease;
        }

        .hp-fill.yellow {
            background: var(--yellow);
        }

        .hp-fill.red {
            background: var(--red);
        }

        .enemy-sprite,
        .player-sprite {
            position: absolute;
            width: 180px;
            height: 180px;
            object-fit: contain;
            image-rendering: pixelated;
            z-index: 2;
        }

        .enemy-sprite {
            right: 118px;
            top: 90px;
        }

        .player-sprite {
            left: 110px;
            bottom: 124px;
            width: 210px;
            height: 210px;
        }

        .sprite-attack {
            animation: attackFlash 280ms ease;
        }

        .sprite-hit {
            animation: damageShake 320ms ease;
        }

        .sprite-faint {
            opacity: 0.25;
            transform: translateY(22px);
            transition: 450ms ease;
        }

        .dialogue {
            min-height: 140px;
            padding: 18px;
            background: #fffdf0;
        }

        .dialogue-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 310px;
            gap: 16px;
            align-items: stretch;
        }

        .dialogue-text {
            min-height: 92px;
            font-size: 13px;
            line-height: 2;
        }

        .battle-menu,
        .move-menu,
        .team-menu {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .move-menu,
        .team-menu {
            display: none;
        }

        .move-btn,
        .team-btn,
        .menu-btn {
            min-height: 44px;
            padding: 8px;
            border: 3px solid var(--ink);
            background: #fff8df;
            cursor: pointer;
            font-size: 10px;
            line-height: 1.4;
            text-align: left;
        }

        .move-btn:disabled,
        .team-btn:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        .team-menu {
            grid-template-columns: 1fr;
            max-height: 170px;
            overflow: auto;
        }

        .status-overlay {
            display: none;
            gap: 14px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            padding: 14px;
            margin-top: 16px;
            text-align: center;
        }

        .status-overlay.is-visible {
            display: flex;
        }

        .hint {
            margin: 12px 0 0;
            color: #5f5a4b;
            font-size: 9px;
            line-height: 1.8;
        }

        .error {
            margin: 0 0 14px;
            padding: 12px;
            border: 3px solid #921b26;
            background: #ffe1e5;
            color: #921b26;
            font-size: 10px;
            line-height: 1.7;
        }

        @keyframes attackFlash {
            0%, 100% { filter: none; transform: translateX(0); }
            50% { filter: brightness(1.7); transform: translateX(18px); }
        }

        @keyframes damageShake {
            0%, 100% { transform: translateX(0); filter: none; }
            25% { transform: translateX(-10px); filter: brightness(1.8); }
            50% { transform: translateX(10px); filter: brightness(1.8); }
            75% { transform: translateX(-6px); filter: brightness(1.8); }
        }

        @media (max-width: 860px) {
            .selection-layout,
            .dialogue-grid {
                grid-template-columns: 1fr;
            }

            .team-panel {
                position: static;
            }

            .battle-scene {
                height: 460px;
            }

            .enemy-box,
            .player-box {
                width: calc(100% - 32px);
                left: 16px;
                right: 16px;
            }

            .enemy-sprite {
                right: 36px;
                top: 140px;
            }

            .player-sprite {
                left: 26px;
                bottom: 134px;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <div class="topbar">
            <h1 class="title">Modo Batalha</h1>
            <a class="pixel-btn" href="{{ route('pokedex.index') }}">Voltar para Pokedex</a>
        </div>

        @if ($mode === 'selection')
            <section class="selection-layout">
                <div class="pixel-panel">
                    <div style="padding: 16px 16px 0;">
                        <h2 style="margin: 0; font-size: 16px; line-height: 1.6;">Escolha seu time</h2>
                        <p class="hint">Selecione de 3 a 6 Pokemon. Os dados completos sao carregados pela PokeAPI quando a batalha comeca.</p>
                    </div>

                    @if ($errors->any())
                        <div class="error" style="margin: 16px;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="battle-search-bar">
                        <input
                            id="battleSearch"
                            class="battle-search-input"
                            type="search"
                            placeholder="Buscar por nome ou numero..."
                            autocomplete="off"
                            aria-label="Buscar Pokemon"
                        >
                        <button id="battleSearchClear" class="battle-search-clear" type="button" aria-label="Limpar busca">&times;</button>
                        <span id="battleSearchCount" class="battle-search-count"></span>
                    </div>

                    <div class="selection-grid" id="selectionGrid">
                        @forelse ($availablePokemon as $pokemon)
                            <button
                                class="select-card"
                                type="button"
                                data-select-pokemon
                                data-id="{{ $pokemon['id'] }}"
                                data-name="{{ $pokemon['name'] }}"
                                data-image="{{ $pokemon['image'] }}"
                                data-hp="{{ $pokemon['hp'] }}"
                                data-types="{{ implode(',', $pokemon['types']) }}"
                                data-detail-url="{{ route('pokedex.show', $pokemon['id']) }}"
                            >
                                <img src="{{ $pokemon['image'] }}" alt="{{ $pokemon['name'] }}">
                                <span class="select-name">#{{ str_pad((string) $pokemon['id'], 3, '0', STR_PAD_LEFT) }} {{ $pokemon['name'] }}</span>
                                <span class="select-meta">
                                    {{ $pokemon['types'] ? implode(' / ', $pokemon['types']) : 'PokeAPI' }}<br>
                                    HP: {{ $pokemon['hp'] ?: 'ao iniciar' }}
                                </span>
                            </button>
                        @empty
                            <p class="hint" style="padding: 16px;">Nenhum Pokemon disponivel. Cadastre um Pokemon ou tente novamente quando a PokeAPI estiver online.</p>
                        @endforelse
                    </div>
                </div>

                <aside class="pixel-panel team-panel">
                    <h2 style="margin: 0; font-size: 14px;">Seu time</h2>
                    <div class="slot-bar"><div class="slot-bar-fill" id="slotBar"></div></div>
                    <p class="hint" id="slotCounter">0/6 selecionados</p>

                    <form method="POST" action="{{ route('batalha.iniciar') }}" id="startBattleForm">
                        @csrf
                        <div id="selectedInputs"></div>
                        <div class="selected-list" id="selectedList"></div>
                        <button class="pixel-btn primary" id="startBattleButton" type="submit" disabled style="width: 100%; margin-top: 14px;">
                            Iniciar Batalha
                        </button>
                    </form>

                    <ol class="hint">
                        @foreach ($leaders as $index => $leader)
                            <li>{{ $index + 1 }}. {{ $leader['name'] }} - {{ $leader['type'] }} Lv. {{ $leader['level'] }}</li>
                        @endforeach
                    </ol>
                </aside>
            </section>
        @else
            <section class="battle-shell">
                <div class="pixel-panel battle-scene">
                    <div class="enemy-box">
                        <div class="battle-name">
                            <span id="enemyName">---</span>
                            <span id="enemyLevel">Lv. --</span>
                        </div>
                        <div class="hp-label">HP</div>
                        <div class="hp-shell"><div class="hp-fill" id="enemyHpBar"></div></div>
                    </div>

                    <img class="enemy-sprite" id="enemySprite" src="" alt="Pokemon inimigo">
                    <img class="player-sprite" id="playerSprite" src="" alt="Pokemon jogador">

                    <div class="player-box">
                        <div class="battle-name">
                            <span id="playerName">---</span>
                            <span id="playerLevel">Lv. --</span>
                        </div>
                        <div class="hp-label">HP <span id="playerHpText"></span></div>
                        <div class="hp-shell"><div class="hp-fill" id="playerHpBar"></div></div>
                    </div>
                </div>

                <div class="pixel-panel dialogue">
                    <div class="dialogue-grid">
                        <div class="dialogue-text" id="dialogueText">O que faremos?</div>

                        <div>
                            <div class="battle-menu" id="battleMenu">
                                <button class="menu-btn" type="button" data-menu="fight">LUTAR</button>
                                <button class="menu-btn" type="button" data-menu="team">POKEMON</button>
                                <button class="menu-btn" type="button" data-action="bag">MOCHILA</button>
                                <button class="menu-btn" type="button" data-action="run">FUGIR</button>
                            </div>

                            <div class="move-menu" id="moveMenu"></div>
                            <div class="team-menu" id="teamMenu"></div>
                        </div>
                    </div>
                </div>

                <div class="pixel-panel status-overlay" id="statusOverlay">
                    <span id="statusText"></span>
                    <button class="pixel-btn primary" id="nextLeaderButton" type="button">Proximo Lider</button>
                    <a class="pixel-btn" id="retryLeaderLink" href="{{ route('batalha.resetar', ['atual' => 1]) }}">Tentar Lider Atual</a>
                    <a class="pixel-btn" href="{{ route('batalha.resetar') }}">Reiniciar</a>
                </div>
            </section>
        @endif
    </main>

    <script>
        const mode = @json($mode);
        const routes = {
            turno: @json(route('batalha.turno')),
            proximo: @json(route('batalha.proximo')),
            resetar: @json(route('batalha.resetar')),
        };
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            }[char]));
        }

        if (mode === 'selection') {
            const selected = new Map();
            const grid = document.getElementById('selectionGrid');
            const selectedList = document.getElementById('selectedList');
            const selectedInputs = document.getElementById('selectedInputs');
            const slotBar = document.getElementById('slotBar');
            const slotCounter = document.getElementById('slotCounter');
            const startButton = document.getElementById('startBattleButton');
            const battleSearch = document.getElementById('battleSearch');
            const battleSearchClear = document.getElementById('battleSearchClear');
            const battleSearchCount = document.getElementById('battleSearchCount');

            const allCards = () => Array.from(grid.querySelectorAll('[data-select-pokemon]'));

            function applyBattleSearch() {
                const q = (battleSearch.value || '').trim().toLowerCase();
                let visible = 0;
                allCards().forEach((card) => {
                    const name = (card.dataset.name || '').toLowerCase();
                    const num = String(card.dataset.id || '');
                    const numPadded = num.padStart(3, '0');
                    const match = !q || name.includes(q) || num.includes(q) || numPadded.includes(q);
                    card.classList.toggle('is-hidden', !match);
                    if (match) visible++;
                });
                const total = allCards().length;
                battleSearchCount.textContent = q ? `${visible}/${total}` : '';
                battleSearchClear.style.display = q ? 'flex' : 'none';
            }

            battleSearch?.addEventListener('input', applyBattleSearch);

            battleSearchClear?.addEventListener('click', () => {
                battleSearch.value = '';
                applyBattleSearch();
                battleSearch.focus();
            });

            function renderSelection() {
                const count = selected.size;
                slotCounter.textContent = `${count}/6 selecionados`;
                slotBar.style.width = `${(count / 6) * 100}%`;
                startButton.disabled = count < 3;
                selectedInputs.innerHTML = [...selected.keys()].map((id) => `<input type="hidden" name="pokemon[]" value="${id}">`).join('');
                selectedList.innerHTML = [...selected.values()].map((pokemon) => `
                    <div class="selected-item" data-selected-id="${pokemon.id}">
                        <img src="${escapeHtml(pokemon.image)}" alt="${escapeHtml(pokemon.name)}">
                        <div>
                            <strong>${escapeHtml(pokemon.name)}</strong><br>
                            <span>${escapeHtml(pokemon.types || 'Tipos via API')}</span><br>
                            <span>HP: ${escapeHtml(pokemon.hp || 'carregando')}</span>
                        </div>
                    </div>
                `).join('');
            }

            async function hydrateSelectedPokemon(card, pokemon) {
                if (pokemon.hp && pokemon.types) {
                    return;
                }

                try {
                    const response = await fetch(card.dataset.detailUrl, { headers: { Accept: 'application/json' } });
                    const data = await response.json();

                    if (!response.ok || !data.pokemon) {
                        return;
                    }

                    const hp = (data.pokemon.stats || []).find((stat) => stat.name === 'hp');
                    pokemon.hp = hp ? hp.value : pokemon.hp;
                    pokemon.types = (data.pokemon.types || []).join(' / ') || pokemon.types;
                    selected.set(pokemon.id, pokemon);
                    renderSelection();
                } catch (error) {
                    pokemon.hp = pokemon.hp || 'ao iniciar';
                    selected.set(pokemon.id, pokemon);
                    renderSelection();
                }
            }

            grid?.addEventListener('click', (event) => {
                const card = event.target.closest('[data-select-pokemon]');

                if (!card) {
                    return;
                }

                const id = card.dataset.id;

                if (selected.has(id)) {
                    selected.delete(id);
                    card.classList.remove('is-selected');
                    renderSelection();
                    return;
                }

                if (selected.size >= 6) {
                    return;
                }

                const pokemon = {
                    id,
                    name: card.dataset.name,
                    image: card.dataset.image,
                    hp: card.dataset.hp || '',
                    types: (card.dataset.types || '').replaceAll(',', ' / '),
                };

                selected.set(id, pokemon);
                card.classList.add('is-selected');
                renderSelection();
                hydrateSelectedPokemon(card, pokemon);
            });

            renderSelection();
        } else {
            let battle = @json($battle);
            const enemyName = document.getElementById('enemyName');
            const enemyLevel = document.getElementById('enemyLevel');
            const enemyHpBar = document.getElementById('enemyHpBar');
            const enemySprite = document.getElementById('enemySprite');
            const playerName = document.getElementById('playerName');
            const playerLevel = document.getElementById('playerLevel');
            const playerHpBar = document.getElementById('playerHpBar');
            const playerHpText = document.getElementById('playerHpText');
            const playerSprite = document.getElementById('playerSprite');
            const dialogueText = document.getElementById('dialogueText');
            const battleMenu = document.getElementById('battleMenu');
            const moveMenu = document.getElementById('moveMenu');
            const teamMenu = document.getElementById('teamMenu');
            const statusOverlay = document.getElementById('statusOverlay');
            const statusText = document.getElementById('statusText');
            const nextLeaderButton = document.getElementById('nextLeaderButton');
            let busy = false;

            function hpPercent(pokemon) {
                return Math.max(0, Math.min(100, (pokemon.current_hp / pokemon.max_hp) * 100));
            }

            function hpClass(percent) {
                if (percent <= 25) return 'red';
                if (percent <= 50) return 'yellow';
                return '';
            }

            function setHpBar(bar, pokemon) {
                const percent = hpPercent(pokemon);
                bar.style.width = `${percent}%`;
                bar.classList.remove('yellow', 'red');
                const klass = hpClass(percent);
                if (klass) {
                    bar.classList.add(klass);
                }
            }

            function showMainMenu() {
                battleMenu.style.display = 'grid';
                moveMenu.style.display = 'none';
                teamMenu.style.display = 'none';
            }

            function showMoveMenu() {
                battleMenu.style.display = 'none';
                moveMenu.style.display = 'grid';
                teamMenu.style.display = 'none';
            }

            function showTeamMenu() {
                battleMenu.style.display = 'none';
                moveMenu.style.display = 'none';
                teamMenu.style.display = 'grid';
            }

            function renderBattle() {
                const player = battle.player;
                const enemy = battle.enemy;

                enemyName.textContent = enemy.name;
                enemyLevel.textContent = `Lv. ${enemy.level}`;
                enemySprite.src = enemy.sprite_front;
                enemySprite.classList.toggle('sprite-faint', enemy.current_hp <= 0);
                setHpBar(enemyHpBar, enemy);

                playerName.textContent = player.name;
                playerLevel.textContent = `Lv. ${player.level}`;
                playerHpText.textContent = `${player.current_hp}/${player.max_hp}`;
                playerSprite.src = player.sprite_back;
                playerSprite.classList.toggle('sprite-faint', player.current_hp <= 0);
                setHpBar(playerHpBar, player);

                moveMenu.innerHTML = player.moves.map((move, index) => `
                    <button class="move-btn" type="button" data-move-index="${index}" ${move.pp <= 0 ? 'disabled' : ''}>
                        ${escapeHtml(move.label)}<br>
                        <small>${escapeHtml(move.type.toUpperCase())} PP: ${move.pp}/${move.max_pp}</small>
                    </button>
                `).join('') + '<button class="move-btn" type="button" data-back-menu>VOLTAR</button>';

                teamMenu.innerHTML = battle.player_team.map((pokemon, index) => `
                    <button class="team-btn" type="button" data-switch-index="${index}" ${pokemon.current_hp <= 0 || index === battle.active_player ? 'disabled' : ''}>
                        ${escapeHtml(pokemon.name)} HP ${pokemon.current_hp}/${pokemon.max_hp}
                    </button>
                `).join('') + '<button class="team-btn" type="button" data-back-menu>VOLTAR</button>';

                renderStatus();
            }

            function renderStatus() {
                statusOverlay.classList.toggle('is-visible', battle.status !== 'battle');
                nextLeaderButton.style.display = battle.status === 'leader_victory' ? 'inline-block' : 'none';
                document.getElementById('retryLeaderLink').style.display = battle.status === 'game_over' ? 'inline-block' : 'none';

                if (battle.status === 'leader_victory') {
                    statusText.textContent = `${battle.leader.name} derrotado! Seu time sera curado parcialmente.`;
                } else if (battle.status === 'champion_victory') {
                    statusText.textContent = 'Voce venceu todos os lideres. Campeao da Liga!';
                } else if (battle.status === 'game_over') {
                    statusText.textContent = 'Game Over. Reinicie para tentar novamente.';
                }
            }

            async function typeMessages(messages) {
                for (const message of messages || []) {
                    dialogueText.textContent = '';
                    for (const char of message) {
                        dialogueText.textContent += char;
                        await new Promise((resolve) => setTimeout(resolve, 14));
                    }
                    await new Promise((resolve) => setTimeout(resolve, 420));
                }

                if (!messages || messages.length === 0) {
                    dialogueText.textContent = `O que ${battle.player.name} fara?`;
                }
            }

            async function postTurn(payload) {
                if (busy || battle.status !== 'battle') {
                    return;
                }

                busy = true;
                playerSprite.classList.add('sprite-attack');
                enemySprite.classList.add('sprite-hit');

                try {
                    const response = await fetch(routes.turno, {
                        method: 'POST',
                        headers: {
                            Accept: 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify(payload),
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Erro no turno.');
                    }

                    battle = data.battle;
                    renderBattle();
                    showMainMenu();
                    await typeMessages(battle.messages);
                } catch (error) {
                    dialogueText.textContent = error.message || 'Erro no turno.';
                } finally {
                    playerSprite.classList.remove('sprite-attack');
                    enemySprite.classList.remove('sprite-hit');
                    busy = false;
                }
            }

            battleMenu.addEventListener('click', (event) => {
                const button = event.target.closest('button');

                if (!button) return;

                if (button.dataset.menu === 'fight') {
                    showMoveMenu();
                    return;
                }

                if (button.dataset.menu === 'team') {
                    showTeamMenu();
                    return;
                }

                if (button.dataset.action) {
                    postTurn({ action: button.dataset.action });
                }
            });

            moveMenu.addEventListener('click', (event) => {
                const back = event.target.closest('[data-back-menu]');
                const move = event.target.closest('[data-move-index]');

                if (back) {
                    showMainMenu();
                    return;
                }

                if (move) {
                    postTurn({ action: 'move', move_index: Number(move.dataset.moveIndex) });
                }
            });

            teamMenu.addEventListener('click', (event) => {
                const back = event.target.closest('[data-back-menu]');
                const teammate = event.target.closest('[data-switch-index]');

                if (back) {
                    showMainMenu();
                    return;
                }

                if (teammate) {
                    postTurn({ action: 'switch', switch_index: Number(teammate.dataset.switchIndex) });
                }
            });

            nextLeaderButton.addEventListener('click', async () => {
                await fetch(routes.proximo, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                });
                window.location.reload();
            });

            renderBattle();
            typeMessages(battle.messages);
        }
    </script>
</body>
</html>
