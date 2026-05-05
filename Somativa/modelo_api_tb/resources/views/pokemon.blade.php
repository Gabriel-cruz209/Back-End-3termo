<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokedex Premium</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            transition: background 0.45s ease;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.24);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.35);
        }

        .shadow-soft {
            box-shadow: 0 32px 80px rgba(15, 23, 42, 0.12);
        }

        .loading-overlay {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(15, 23, 42, 0.65);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .spinner {
            width: 56px;
            height: 56px;
            border: 5px solid rgba(255, 255, 255, 0.22);
            border-top-color: #6366f1;
            border-radius: 9999px;
            animation: spin 1s linear infinite;
        }

        .fade-in-up {
            opacity: 0;
            transform: translateY(18px);
            animation: fadeInUp 0.7s ease-out forwards;
        }

        .card-hover {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 28px 50px rgba(15, 23, 42, 0.12);
        }

        .type-pill {
            min-width: 88px;
        }

        .info-box {
            min-width: 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .error-message {
            display: none;
            color: #dc2626;
        }

        .error-message.active {
            display: block;
        }

        .evolution-arrow {
            font-size: 1.8rem;
            color: rgba(15, 23, 42, 0.45);
        }

        .variant-text {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        .variant-card {
            width: 100%;
            min-height: 220px;
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 1rem;
            max-width: 100%;
        }

        .variant-card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            text-align: center;
        }

        .variant-card-title {
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.35rem;
            width: 100%;
        }

        .variant-card .variant-label {
            margin-bottom: 0.35rem;
            letter-spacing: 0.08em;
        }

        .variant-card .variant-name {
            line-height: 1.15;
        }

        .variant-card .variant-type {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: fit-content;
        }

        .variant-card img {
            min-width: 100px;
            min-height: 100px;
            width: 100px;
            height: 100px;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body id="appBody" style="background: linear-gradient(135deg, #c7d2fe 0%, #f5f3ff 100%);">
    <div id="loadingOverlay" class="loading-overlay" aria-hidden="true">
        <div class="flex flex-col items-center gap-4 rounded-3xl bg-slate-950/85 px-8 py-8 shadow-2xl">
            <div class="spinner"></div>
            <p class="text-sm font-semibold text-white">Buscando Pokémon...</p>
        </div>
    </div>

    <div class="relative mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="glass-card shadow-soft rounded-[32px] border border-white/30 p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-sm uppercase tracking-[0.3em] text-indigo-600">Pokedex Premium</p>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-950">Busque qualquer Pokémon</h1>
                    <p class="mt-3 max-w-xl text-slate-600">Use o campo abaixo para pesquisar por nome ou ID. O resultado vem com evolução, variantes e um visual moderno em glassmorphism.</p>
                </div>
                <div class="w-full max-w-md">
                    <div class="relative flex items-center gap-2 rounded-full bg-white/90 p-2 shadow-sm">
                        <input id="searchInput" type="text" placeholder="Ex: pikachu ou 25"
                               class="w-full rounded-full border border-slate-200 bg-white px-5 py-3 text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                               autocomplete="off">
                        <button id="searchButton" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                            Buscar
                        </button>
                    </div>
                    <p id="errorMessage" class="error-message mt-3 text-sm">Erro aqui</p>
                </div>
            </div>

            <section class="mt-10 grid gap-10 lg:grid-cols-[1.4fr_1fr]">
                <div class="space-y-8">
                    <div id="pokemonCard" class="glass-card rounded-[32px] border border-white/40 p-8 shadow-sm card-hover fade-in-up">
                        <div class="flex flex-col gap-8 lg:flex-row lg:items-center">
                            <div class="relative flex-1 overflow-hidden rounded-[32px] bg-slate-900/5 p-6">
                                <div class="absolute -right-10 -top-10 h-36 w-36 rounded-full bg-white/20 blur-2xl"></div>
                                <div class="relative z-10 flex flex-col items-center gap-6">
                                    <div id="typeBadge" class="inline-flex rounded-full bg-indigo-100 px-4 py-2 text-sm font-semibold text-indigo-700 uppercase shadow-sm"></div>
                                    <img id="pokemonImage" src="" alt="Pokémon" class="h-52 w-52 rounded-[32px] object-contain transition-all duration-500">
                                </div>
                            </div>

                            <div class="flex-1">
                                <div class="flex flex-col gap-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span id="pokemonId" class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500"></span>
                                        <div id="typeBadges" class="flex flex-wrap gap-2"></div>
                                    </div>
                                    <h2 id="pokemonName" class="text-5xl font-semibold tracking-tight text-slate-950"></h2>
                                    <p id="pokemonDescription" class="text-sm leading-7 text-slate-600">Carregue um Pokémon para ver detalhes, evolução e variantes.</p>
                                </div>

                                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                    <div class="info-box rounded-[28px] bg-white/80 p-5 shadow-sm">
                                        <p class="text-sm text-slate-500">Altura</p>
                                        <p id="pokemonHeight" class="mt-3 text-xl font-semibold text-slate-950"></p>
                                    </div>
                                    <div class="info-box rounded-[28px] bg-white/80 p-5 shadow-sm">
                                        <p class="text-sm text-slate-500">Peso</p>
                                        <p id="pokemonWeight" class="mt-3 text-xl font-semibold text-slate-950"></p>
                                    </div>
                                    <div class="info-box rounded-[28px] bg-white/80 p-5 shadow-sm">
                                        <p class="text-sm text-slate-500">Experiência</p>
                                        <p id="pokemonExp" class="mt-3 text-xl font-semibold text-slate-950"></p>
                                    </div>
                                    <div class="info-box rounded-[28px] bg-white/80 p-5 shadow-sm">
                                        <p class="text-sm text-slate-500">Tipo principal</p>
                                        <p id="pokemonPrimaryType" class="mt-3 text-xl font-semibold text-slate-950"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10">
                            <h3 class="text-lg font-semibold text-slate-950">Stats</h3>
                            <div id="statsContainer" class="mt-5 space-y-4"></div>
                        </div>
                    </div>

                    <div class="glass-card rounded-[32px] border border-white/40 p-8 shadow-sm card-hover fade-in-up">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Evolução</p>
                                <h3 class="mt-2 text-2xl font-semibold text-slate-950">Linha evolutiva</h3>
                            </div>
                        </div>
                        <div id="evolutionContainer" class="mt-6 flex items-center gap-3 overflow-auto py-2"></div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="glass-card rounded-[32px] border border-white/40 p-8 shadow-sm card-hover fade-in-up">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Formas</p>
                                <h3 class="mt-2 text-2xl font-semibold text-slate-950">Variações especiais</h3>
                            </div>
                        </div>
                        <div id="variantContainer" class="mt-6 flex flex-col gap-5"></div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        const initialPokemon = @json($pokemon ?? null);
        const typeGradients = {
            normal: 'linear-gradient(135deg, #e2e8f0 0%, #f8fafc 100%)',
            fire: 'linear-gradient(135deg, #fda4af 0%, #f97316 100%)',
            water: 'linear-gradient(135deg, #93c5fd 0%, #2563eb 100%)',
            grass: 'linear-gradient(135deg, #86efac 0%, #16a34a 100%)',
            electric: 'linear-gradient(135deg, #fef08a 0%, #f59e0b 100%)',
            psychic: 'linear-gradient(135deg, #fbcfe8 0%, #8b5cf6 100%)',
            ice: 'linear-gradient(135deg, #bae6fd 0%, #38bdf8 100%)',
            dragon: 'linear-gradient(135deg, #c4b5fd 0%, #4338ca 100%)',
            dark: 'linear-gradient(135deg, #cbd5e1 0%, #334155 100%)',
            fairy: 'linear-gradient(135deg, #f9a8d4 0%, #ec4899 100%)',
            rock: 'linear-gradient(135deg, #f7fee7 0%, #78716c 100%)',
            ground: 'linear-gradient(135deg, #fde68a 0%, #b45309 100%)',
            flying: 'linear-gradient(135deg, #e0f2fe 0%, #0ea5e9 100%)',
            bug: 'linear-gradient(135deg, #d9f99d 0%, #22c55e 100%)',
            poison: 'linear-gradient(135deg, #ddd6fe 0%, #7c3aed 100%)',
            steel: 'linear-gradient(135deg, #e2e8f0 0%, #94a3b8 100%)',
            ghost: 'linear-gradient(135deg, #c7d2fe 0%, #4f46e5 100%)'
        };
        const typeColors = {
            fire: 'bg-red-500', water: 'bg-blue-500', grass: 'bg-green-500', electric: 'bg-yellow-400', psychic: 'bg-purple-500', ice: 'bg-cyan-400', dragon: 'bg-indigo-500', dark: 'bg-slate-800', fairy: 'bg-pink-400', normal: 'bg-slate-500', fighting: 'bg-orange-500', poison: 'bg-purple-600', ground: 'bg-amber-500', flying: 'bg-sky-400', bug: 'bg-lime-500', rock: 'bg-stone-500', ghost: 'bg-violet-500', steel: 'bg-slate-500'
        };

        const appBody = document.getElementById('appBody');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const errorMessage = document.getElementById('errorMessage');
        const pokemonName = document.getElementById('pokemonName');
        const pokemonId = document.getElementById('pokemonId');
        const typeBadge = document.getElementById('typeBadge');
        const typeBadges = document.getElementById('typeBadges');
        const pokemonImage = document.getElementById('pokemonImage');
        const pokemonDescription = document.getElementById('pokemonDescription');
        const pokemonHeight = document.getElementById('pokemonHeight');
        const pokemonWeight = document.getElementById('pokemonWeight');
        const pokemonExp = document.getElementById('pokemonExp');
        const pokemonPrimaryType = document.getElementById('pokemonPrimaryType');
        const statsContainer = document.getElementById('statsContainer');
        const evolutionContainer = document.getElementById('evolutionContainer');
        const variantContainer = document.getElementById('variantContainer');

        function toggleLoading(active) {
            loadingOverlay.classList.toggle('active', active);
        }

        function setError(message) {
            errorMessage.textContent = message;
            errorMessage.classList.add('active');
        }

        function clearError() {
            errorMessage.textContent = '';
            errorMessage.classList.remove('active');
        }

        function getGradient(type) {
            return typeGradients[type] || typeGradients.normal;
        }

        function getTypeColor(type) {
            return typeColors[type] || 'bg-slate-500';
        }

        function renderTypes(types) {
            typeBadges.innerHTML = '';
            types.forEach(type => {
                const pill = document.createElement('span');
                pill.className = `type-pill inline-flex items-center rounded-full px-4 py-2 text-xs font-semibold uppercase text-white ${getTypeColor(type.type.name)}`;
                pill.textContent = type.type.name;
                typeBadges.appendChild(pill);
            });
        }

        function renderStats(stats) {
            statsContainer.innerHTML = '';
            stats.forEach(stat => {
                const statLine = document.createElement('div');
                statLine.className = 'space-y-2';
                statLine.innerHTML = `
                    <div class="flex items-center justify-between text-sm font-semibold text-slate-700">
                        <span>${stat.stat.name.toUpperCase()}</span>
                        <span>${stat.base_stat}</span>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-slate-200">
                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width: ${Math.min(100, (stat.base_stat / 255) * 100)}%"></div>
                    </div>
                `;
                statsContainer.appendChild(statLine);
            });
        }

        function renderEvolution(chain) {
            evolutionContainer.innerHTML = '';
            if (!chain.length) {
                evolutionContainer.innerHTML = '<p class="text-sm text-slate-500">Esta espécie não possui dados de evolução.</p>';
                return;
            }
            chain.forEach((stage, index) => {
                const card = document.createElement('div');
                card.className = 'flex min-w-[130px] flex-col items-center gap-3 rounded-[28px] bg-white/90 px-4 py-4 shadow-sm';
                card.innerHTML = `
                    <img src="${stage.sprite}" alt="${stage.name}" class="h-20 w-20 object-contain cursor-pointer">
                    <span class="text-sm font-semibold text-slate-950 truncate block">${stage.name}</span>
                `;
                const img = card.querySelector('img');
                img.addEventListener('click', () => {
                    fetchPokemon(stage.name);
                });
                evolutionContainer.appendChild(card);
                if (index < chain.length - 1) {
                    const arrow = document.createElement('span');
                    arrow.className = 'evolution-arrow';
                    arrow.innerHTML = '&rarr;';
                    evolutionContainer.appendChild(arrow);
                }
            });
        }

        function renderVariants(variants) {
            variantContainer.innerHTML = '';
            if (!variants.length) {
                variantContainer.innerHTML = '<p class="text-sm text-slate-500">Sem formas regionais ou Mega Evoluções.</p>';
                return;
            }
            variants.forEach(variant => {
                const card = document.createElement('div');
                card.className = 'card-hover variant-card rounded-[28px] bg-white/90 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg';
                card.innerHTML = `
                    <div class="variant-card-body">
                        <div class="flex items-center justify-center rounded-[24px] bg-slate-100 p-2">
                            <img src="${variant.sprite}" alt="${variant.name}" class="object-contain cursor-pointer">
                        </div>
                        <div class="variant-card-title variant-text">
                            <p class="variant-label text-sm uppercase tracking-[0.2em] text-slate-400">${variant.label}</p>
                            <h4 class="mt-2 text-lg font-semibold text-slate-950 variant-name">${variant.name}</h4>
                            <span class="variant-type rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase text-slate-700">${variant.type}</span>
                        </div>
                    </div>
                `;
                const img = card.querySelector('img');
                img.addEventListener('click', () => {
                    fetchPokemon(variant.query_name || variant.name);
                });
                variantContainer.appendChild(card);
            });
        }

        function setPokemonData(pokemon) {
            clearError();
            appBody.style.background = getGradient(pokemon.primary_type);
            pokemonName.textContent = pokemon.name;
            pokemonId.textContent = `#${pokemon.id}`;
            typeBadge.textContent = pokemon.primary_type;
            pokemonImage.src = pokemon.sprites.other?.['official-artwork']?.front_default || pokemon.sprites.front_default;
            pokemonImage.alt = pokemon.name;
            renderTypes(pokemon.types);
            pokemonDescription.textContent = `Um ${pokemon.primary_type} com ${pokemon.base_experience} de experiência base.`;
            pokemonHeight.textContent = `${(pokemon.height / 10).toFixed(1)} m`;
            pokemonWeight.textContent = `${(pokemon.weight / 10).toFixed(1)} kg`;
            pokemonExp.textContent = `${pokemon.base_experience}`;
            pokemonPrimaryType.textContent = pokemon.primary_type;
            renderStats(pokemon.stats);
            renderEvolution(pokemon.evolution_chain);
            renderVariants(pokemon.variant_forms);
        }

        async function fetchPokemon(query = '') {
            clearError();
            toggleLoading(true);
            const url = `/api/pokemon${query ? `?query=${encodeURIComponent(query)}` : ''}`;
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || 'Erro ao buscar Pokémon.');
                }
                setPokemonData(data.pokemon);
            } catch (error) {
                setError(error.message);
            } finally {
                toggleLoading(false);
            }
        }

        function debounce(fn, delay) {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn(...args), delay);
            };
        }

        const debouncedSearch = debounce(() => {
            const query = searchInput.value.trim();
            if (!query) {
                return;
            }
            fetchPokemon(query);
        }, 600);

        searchInput.addEventListener('input', () => {
            clearError();
            if (!searchInput.value.trim()) {
                return;
            }
            debouncedSearch();
        });

        searchButton.addEventListener('click', () => {
            const query = searchInput.value.trim();
            fetchPokemon(query);
        });

        document.addEventListener('DOMContentLoaded', () => {
            if (initialPokemon) {
                setPokemonData(initialPokemon);
            }
        });
    </script>
</body>
</html>