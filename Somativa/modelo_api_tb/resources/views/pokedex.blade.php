@php
    $typeColors = [
        'normal' => '#64748b',
        'fire' => '#ef4444',
        'water' => '#2563eb',
        'electric' => '#facc15',
        'grass' => '#16a34a',
        'ice' => '#38bdf8',
        'fighting' => '#ea580c',
        'poison' => '#9333ea',
        'ground' => '#b45309',
        'flying' => '#0ea5e9',
        'psychic' => '#db2777',
        'bug' => '#65a30d',
        'rock' => '#78716c',
        'ghost' => '#4f46e5',
        'dragon' => '#7c3aed',
        'dark' => '#1f2937',
        'steel' => '#64748b',
        'fairy' => '#ec4899',
    ];
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pokedex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .type-toggle {
            border-color: #27272a;
            color: #71717a;
            background: rgba(24, 24, 27, 0.5);
        }

        .type-toggle.is-active {
            border-color: var(--type-color);
            color: #ffffff;
            background: var(--type-color);
            box-shadow: 0 0 20px color-mix(in srgb, var(--type-color) 40%, transparent);
        }

        .status-toggle.is-active {
            border-color: #dc2626;
            background: #dc2626;
            color: #ffffff;
            box-shadow: 0 0 15px rgba(220, 38, 38, 0.3);
        }

        .skeleton-card {
            min-height: 284px;
            background: linear-gradient(90deg, #18181b 25%, #27272a 37%, #18181b 63%);
            background-size: 400% 100%;
            animation: shimmer 1.15s ease-in-out infinite;
            border-radius: 1rem;
        }

        @keyframes shimmer {
            0% {
                background-position: 100% 0;
            }
            100% {
                background-position: 0 0;
            }
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 selection:bg-red-600/30 selection:text-white">
    <div
        id="toastMessage"
        class="fixed right-4 top-4 z-[60] hidden max-w-sm rounded-xl px-5 py-4 text-sm font-black text-white shadow-2xl backdrop-blur-md"
        role="status"
    ></div>

    <main class="mx-auto min-h-screen w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="mb-8 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="relative">
                <div class="absolute -left-4 -top-4 h-24 w-24 rounded-full bg-red-600/10 blur-3xl"></div>
                <p class="relative text-sm font-black uppercase tracking-[0.3em] text-red-500 drop-shadow-[0_0_8px_rgba(239,68,68,0.3)]">Pokedex</p>
                <h1 class="relative mt-2 text-4xl font-black tracking-tighter text-white sm:text-5xl lg:text-6xl">
                    Galeria <span class="bg-gradient-to-r from-red-500 to-amber-500 bg-clip-text text-transparent">Pokémon</span>
                </h1>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <button
                    id="openAddPokemonModal"
                    type="button"
                    class="group relative inline-flex items-center justify-center overflow-hidden rounded-xl bg-red-600 px-7 py-4 text-sm font-black text-white shadow-[0_0_20px_rgba(220,38,38,0.4)] transition-all hover:scale-105 hover:bg-red-500 active:scale-95"
                >
                    <span class="mr-2 text-lg">&#10010;</span> Adicionar Novo
                </button>

                <a
                    href="{{ route('batalha.index') }}"
                    class="group relative inline-flex items-center justify-center overflow-hidden rounded-xl bg-zinc-900 px-7 py-4 text-sm font-black text-amber-400 shadow-xl ring-1 ring-zinc-800 transition-all hover:scale-105 hover:bg-zinc-800 active:scale-95"
                >
                    <span class="mr-2 text-lg">&#9876;</span> Arena de Batalha
                </a>

                <div class="rounded-xl border border-zinc-800 bg-zinc-900/50 px-5 py-3 shadow-xl backdrop-blur-sm">
                    <p id="resultCounter" class="text-sm font-black text-zinc-100">
                        Total: {{ $total }}
                    </p>
                    <p id="loadedCounter" class="mt-1 text-xs font-bold text-zinc-500">
                        {{ count($pokemons) }} ativos
                    </p>
                </div>
            </div>
        </section>

        <section class="mb-10 rounded-2xl border border-zinc-800 bg-zinc-900/40 p-6 shadow-2xl backdrop-blur-md">
            <div class="grid gap-6 lg:grid-cols-[1fr_2fr]">
                <div>
                    <label for="pokemonSearch" class="ml-1 text-xs font-black uppercase tracking-widest text-zinc-500">Localizar</label>
                    <div class="mt-3 flex rounded-xl border border-zinc-800 bg-zinc-950/50 transition-all focus-within:border-red-500/50 focus-within:ring-4 focus-within:ring-red-500/10">
                        <input
                            id="pokemonSearch"
                            type="search"
                            value="{{ $filters['q'] }}"
                            placeholder="Ex: Pikachu..."
                            autocomplete="off"
                            class="min-w-0 flex-1 bg-transparent px-5 py-4 text-sm font-bold text-white outline-none placeholder:text-zinc-600"
                        >
                        <button
                            id="clearSearch"
                            type="button"
                            class="hidden w-14 shrink-0 text-xl font-bold text-zinc-600 transition-colors hover:text-white"
                            aria-label="Limpar"
                        >
                            &times;
                        </button>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between gap-3">
                        <p class="ml-1 text-xs font-black uppercase tracking-widest text-zinc-500">Tipagem Elemental</p>
                        <button id="clearFilters" type="button" class="text-xs font-black uppercase tracking-tighter text-red-500 transition-colors hover:text-red-400">
                            Resetar Filtros
                        </button>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2.5">
                        @foreach ($types as $type)
                            <label
                                class="type-toggle cursor-pointer rounded-lg border border-zinc-800 bg-zinc-900/50 px-3.5 py-2.5 text-[10px] font-black uppercase tracking-widest text-zinc-400 transition-all hover:border-zinc-700"
                                style="--type-color: {{ $typeColors[$type] ?? '#64748b' }}"
                                data-type-button
                            >
                                <input
                                    type="checkbox"
                                    value="{{ $type }}"
                                    class="sr-only"
                                    data-type-filter
                                    @checked(in_array($type, $filters['types'], true))
                                >
                                {{ $type }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-6 md:grid-cols-3">
                <div>
                    <label for="generationFilter" class="ml-1 text-xs font-black uppercase tracking-widest text-zinc-500">Região / Geração</label>
                    <select id="generationFilter" class="mt-3 w-full rounded-xl border border-zinc-800 bg-zinc-950/50 px-4 py-4 text-sm font-bold text-white outline-none transition-all focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10">
                        <option value="" class="bg-zinc-900">Todas as Regiões</option>
                        @foreach ($generations as $generation => $range)
                            <option value="{{ $generation }}" @selected($filters['generation'] === $generation) class="bg-zinc-900">
                                {{ $range['label'] }} ({{ $range['min'] }}-{{ $range['max'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <p class="ml-1 text-xs font-black uppercase tracking-widest text-zinc-500">Origem dos Dados</p>
                    <div class="mt-3 flex gap-2">
                        <button type="button" class="status-toggle flex-1 rounded-xl border border-zinc-800 bg-zinc-950/50 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500 transition-all" data-status-filter="all">
                            Todos
                        </button>
                        <button type="button" class="status-toggle flex-1 rounded-xl border border-zinc-800 bg-zinc-950/50 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500 transition-all" data-status-filter="registered">
                            Capturados
                        </button>
                        <button type="button" class="status-toggle flex-1 rounded-xl border border-zinc-800 bg-zinc-950/50 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500 transition-all" data-status-filter="unregistered">
                            Selvagens
                        </button>
                    </div>
                </div>

                <div>
                    <label for="sortFilter" class="ml-1 text-xs font-black uppercase tracking-widest text-zinc-500">Classificação</label>
                    <select id="sortFilter" class="mt-3 w-full rounded-xl border border-zinc-800 bg-zinc-950/50 px-4 py-4 text-sm font-bold text-white outline-none transition-all focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10">
                        <option value="id" @selected($filters['sort'] === 'id') class="bg-zinc-900">Pelo ID (Nº Nacional)</option>
                        <option value="name_asc" @selected($filters['sort'] === 'name_asc') class="bg-zinc-900">Nome (A-Z)</option>
                        <option value="name_desc" @selected($filters['sort'] === 'name_desc') class="bg-zinc-900">Nome (Z-A)</option>
                    </select>
                </div>
            </div>
        </section>

        <div
            id="apiWarning"
            class="{{ $warning ? '' : 'hidden' }} mb-8 rounded-xl border border-red-500/20 bg-red-500/5 px-5 py-4 text-sm font-bold text-red-400 backdrop-blur-md"
        >
            <span class="mr-2">&#9888;</span> {{ $warning }}
        </div>

        <section
            id="pokemonGrid"
            class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            aria-live="polite"
        >
            @foreach ($pokemons as $pokemon)
                <x-pokemon-card :pokemon="$pokemon" />
            @endforeach
        </section>

        <div id="emptyState" class="{{ $total === 0 ? '' : 'hidden' }} mt-20 flex flex-col items-center justify-center rounded-3xl border border-zinc-800 bg-zinc-900/20 px-10 py-20 text-center backdrop-blur-sm">
            <div class="mb-6 text-6xl opacity-20">???</div>
            <h3 class="text-xl font-black text-zinc-300">Nenhum Pokémon detectado</h3>
            <p class="mt-2 text-sm font-bold text-zinc-600">Tente ajustar seus filtros de busca ou regiao.</p>
        </div>

        <div id="scrollSentinel" class="h-20"></div>

        <div class="mt-12 flex justify-center">
            <button
                id="loadMoreButton"
                type="button"
                class="{{ $hasMore ? '' : 'hidden' }} group relative flex items-center gap-3 overflow-hidden rounded-xl bg-zinc-900 px-10 py-5 text-sm font-black uppercase tracking-widest text-white shadow-2xl transition-all hover:bg-zinc-800 active:scale-95 disabled:opacity-50"
            >
                <span>Explorar Mais</span>
                <span class="text-lg transition-transform group-hover:translate-y-1">↓</span>
            </button>
        </div>
    </main>

    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/80 backdrop-blur-xl px-4 py-6">
        <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-[2.5rem] border border-zinc-800 bg-zinc-900 shadow-[0_0_80px_rgba(0,0,0,0.8)]">
            <div class="relative h-48 bg-zinc-950 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-zinc-900"></div>
                <div id="detailHeaderBg" class="absolute inset-0 opacity-20 blur-2xl"></div>
                <div class="relative flex items-start justify-between gap-6 p-8">
                    <div class="min-w-0">
                        <p id="detailNumber" class="text-xs font-black uppercase tracking-[0.5em] text-red-500 drop-shadow-[0_0_8px_rgba(239,68,68,0.6)]"></p>
                        <h2 id="detailName" class="mt-2 truncate text-5xl font-black tracking-tighter text-white"></h2>
                    </div>
                    <button id="closeDetailModal" type="button" class="flex h-12 w-12 items-center justify-center rounded-2xl bg-zinc-800/50 text-3xl font-light text-zinc-400 backdrop-blur-md transition-all hover:bg-red-600 hover:text-white" aria-label="Fechar">
                        &times;
                    </button>
                </div>
            </div>

            <div class="relative -mt-24 px-8 pb-10">
                <div class="grid gap-8 lg:grid-cols-2">
                    <div class="group relative flex aspect-square items-center justify-center rounded-[2rem] border border-zinc-800 bg-zinc-950/80 p-8 shadow-2xl backdrop-blur-md">
                        <div class="absolute inset-0 rounded-[2rem] bg-gradient-to-tr from-red-600/10 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                        <img id="detailImage" src="" alt="" class="relative h-full w-full object-contain transition-transform duration-500 group-hover:scale-110">
                    </div>

                    <div class="flex flex-col justify-center gap-6">
                        <div id="detailTypes" class="flex flex-wrap gap-2.5"></div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-2xl border border-zinc-800 bg-zinc-950/50 p-4 backdrop-blur-sm">
                                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Altura</p>
                                <p id="detailHeight" class="mt-1 text-lg font-black text-white"></p>
                            </div>
                            <div class="rounded-2xl border border-zinc-800 bg-zinc-950/50 p-4 backdrop-blur-sm">
                                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Peso</p>
                                <p id="detailWeight" class="mt-1 text-lg font-black text-white"></p>
                            </div>
                        </div>

                        <div id="detailAbilitiesContainer" class="rounded-2xl border border-zinc-800 bg-zinc-950/50 p-5 backdrop-blur-sm">
                            <p class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Habilidades</p>
                            <div id="detailAbilities" class="mt-3 flex flex-wrap gap-2"></div>
                        </div>

                        <div class="mt-auto space-y-3">
                            <button
                                id="registerPokemonButton"
                                type="button"
                                class="w-full py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all"
                            >
                                Carregando...
                            </button>
                            <button
                                id="deletePokemonButton"
                                type="button"
                                class="w-full py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all"
                            >
                                Liberar Pokémon
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-950/30 p-6 backdrop-blur-sm">
                        <p class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Status Base</p>
                        <div id="detailStats" class="mt-5 grid gap-4"></div>
                    </div>
                </div>

                <div id="detailFlavorContainer" class="mt-8 hidden">
                    <div class="relative rounded-2xl border border-red-500/20 bg-red-500/5 p-6 backdrop-blur-sm">
                        <div class="absolute -left-1 top-6 h-8 w-1 bg-red-600"></div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-red-500/70">Descrição da Pokédex</p>
                        <p id="detailFlavor" class="mt-3 text-sm font-bold leading-relaxed text-zinc-300"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addPokemonModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/80 backdrop-blur-xl px-4 py-6">
        <div class="mx-auto w-full max-w-4xl overflow-hidden rounded-[2.5rem] border border-zinc-800 bg-zinc-900 shadow-2xl">
            <div class="flex items-center justify-between border-b border-zinc-800 p-8">
                <h2 class="text-3xl font-black tracking-tighter text-white">Novo <span class="text-red-500">Pokémon</span></h2>
                <button id="closeAddPokemonModal" type="button" class="text-3xl font-light text-zinc-500 hover:text-white">&times;</button>
            </div>

            <form id="addPokemonForm" class="p-8" enctype="multipart/form-data">
                @csrf
                <div class="grid gap-8 lg:grid-cols-2">
                    <div class="space-y-6">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="ml-1 text-[10px] font-black uppercase tracking-widest text-zinc-500">Nº Pokédex</span>
                                <input name="pokemon_id" type="number" min="1" required class="mt-2 w-full rounded-2xl border border-zinc-800 bg-zinc-950/50 px-4 py-4 text-sm font-bold text-white outline-none focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10">
                            </label>
                            <label class="block">
                                <span class="ml-1 text-[10px] font-black uppercase tracking-widest text-zinc-500">Nome</span>
                                <input name="nome" type="text" minlength="2" required class="mt-2 w-full rounded-2xl border border-zinc-800 bg-zinc-950/50 px-4 py-4 text-sm font-bold text-white outline-none focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10">
                            </label>
                        </div>

                        <label class="block">
                            <span class="ml-1 text-[10px] font-black uppercase tracking-widest text-zinc-500">Apelido (Opcional)</span>
                            <input name="apelido" type="text" class="mt-2 w-full rounded-2xl border border-zinc-800 bg-zinc-950/50 px-4 py-4 text-sm font-bold text-white outline-none focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10">
                        </label>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="ml-1 text-[10px] font-black uppercase tracking-widest text-zinc-500">Tipo Primário</span>
                                <select name="tipo_primario" required class="mt-2 w-full rounded-2xl border border-zinc-800 bg-zinc-950/50 px-4 py-4 text-sm font-bold text-white outline-none focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10">
                                    <option value="" class="bg-zinc-900">Selecione</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}" class="bg-zinc-900">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block">
                                <span class="ml-1 text-[10px] font-black uppercase tracking-widest text-zinc-500">Tipo Secundário</span>
                                <select name="tipo_secundario" class="mt-2 w-full rounded-2xl border border-zinc-800 bg-zinc-950/50 px-4 py-4 text-sm font-bold text-white outline-none focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10">
                                    <option value="" class="bg-zinc-900">Nenhum</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}" class="bg-zinc-900">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <span class="ml-1 text-[10px] font-black uppercase tracking-widest text-zinc-500">Foto Oficial</span>
                        <div class="relative flex aspect-[4/3] items-center justify-center overflow-hidden rounded-[2rem] border-2 border-dashed border-zinc-800 bg-zinc-950/30 transition-all hover:border-red-500/30">
                            <input id="addPokemonImageInput" type="file" name="imagem" accept="image/*" class="absolute inset-0 cursor-pointer opacity-0">
                            <img id="addPokemonImagePreview" src="" alt="" class="hidden h-full w-full object-contain p-6">
                            <div id="addPokemonImagePlaceholder" class="flex flex-col items-center gap-3 text-zinc-600">
                                <span class="text-4xl">&#128247;</span>
                                <span class="text-[10px] font-black uppercase tracking-widest">Enviar Foto</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <span class="ml-1 text-[10px] font-black uppercase tracking-widest text-zinc-500">Status Base</span>
                    <div class="mt-4 grid grid-cols-3 gap-4 lg:grid-cols-6">
                        @foreach(['hp' => 'HP', 'ataque' => 'ATK', 'defesa' => 'DEF', 'sp_ataque' => 'SPA', 'sp_defesa' => 'SPD', 'velocidade' => 'VEL'] as $name => $label)
                            <label class="block">
                                <span class="ml-1 text-[9px] font-black text-zinc-600">{{ $label }}</span>
                                <input type="number" name="{{ $name }}" required min="0" max="999" value="50" class="mt-1 w-full rounded-xl border border-zinc-800 bg-zinc-950/50 px-3 py-3 text-sm font-bold text-white outline-none focus:border-red-500/50 focus:ring-2 focus:ring-red-500/10">
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span class="ml-1 text-[10px] font-black uppercase tracking-widest text-zinc-500">Altura (dm)</span>
                        <input type="number" name="altura" required min="0" value="10" class="mt-2 w-full rounded-2xl border border-zinc-800 bg-zinc-950/50 px-4 py-4 text-sm font-bold text-white outline-none focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10">
                    </label>
                    <label class="block">
                        <span class="ml-1 text-[10px] font-black uppercase tracking-widest text-zinc-500">Peso (hg)</span>
                        <input type="number" name="peso" required min="0" value="100" class="mt-2 w-full rounded-2xl border border-zinc-800 bg-zinc-950/50 px-4 py-4 text-sm font-bold text-white outline-none focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10">
                    </label>
                </div>

                <label class="mt-8 block">
                    <span class="ml-1 text-[10px] font-black uppercase tracking-widest text-zinc-500">Relato da Pokédex</span>
                    <textarea name="flavor_text" rows="3" maxlength="2000" placeholder="Descreva este Pokémon..." class="mt-2 w-full resize-none rounded-2xl border border-zinc-800 bg-zinc-950/50 px-4 py-4 text-sm font-bold text-white outline-none focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10"></textarea>
                </label>

                <p id="addPokemonError" class="mt-6 hidden rounded-2xl border border-red-500/20 bg-red-500/5 px-4 py-4 text-sm font-bold text-red-500"></p>

                <div class="mt-10 flex flex-col-reverse gap-4 sm:flex-row sm:justify-end">
                    <button id="cancelAddPokemonModal" type="button" class="rounded-2xl border border-zinc-800 bg-zinc-900 px-8 py-5 text-xs font-black uppercase tracking-widest text-zinc-400 transition-all hover:bg-zinc-800 hover:text-white">Cancelar</button>
                    <button id="submitAddPokemonButton" type="submit" class="rounded-2xl bg-red-600 px-12 py-5 text-xs font-black uppercase tracking-widest text-white shadow-xl transition-all hover:bg-red-500 active:scale-95 disabled:opacity-50">Registrar Pokémon</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const pokedexConfig = {
            buscarUrl: @json(route('pokedex.buscar')),
            cadastrarUrl: @json(route('pokemon.cadastrar')),
            csrfToken: @json(csrf_token()),
            initialFilters: @json($filters),
            initialTotal: @json($total),
            initialNextOffset: @json($nextOffset),
            initialHasMore: @json($hasMore),
            initialStatusCounts: @json($statusCounts),
            limit: 48,
            typeColors: @json($typeColors),
        };

        const grid = document.getElementById('pokemonGrid');
        const searchInput = document.getElementById('pokemonSearch');
        const clearSearchButton = document.getElementById('clearSearch');
        const clearFiltersButton = document.getElementById('clearFilters');
        const generationFilter = document.getElementById('generationFilter');
        const sortFilter = document.getElementById('sortFilter');
        const typeInputs = Array.from(document.querySelectorAll('[data-type-filter]'));
        const typeButtons = Array.from(document.querySelectorAll('[data-type-button]'));
        const statusButtons = Array.from(document.querySelectorAll('[data-status-filter]'));
        const resultCounter = document.getElementById('resultCounter');
        const loadedCounter = document.getElementById('loadedCounter');
        const emptyState = document.getElementById('emptyState');
        const loadMoreButton = document.getElementById('loadMoreButton');
        const sentinel = document.getElementById('scrollSentinel');
        const apiWarning = document.getElementById('apiWarning');
        const detailModal = document.getElementById('detailModal');
        const closeDetailModal = document.getElementById('closeDetailModal');
        const registerPokemonButton = document.getElementById('registerPokemonButton');
        const deletePokemonButton = document.getElementById('deletePokemonButton');
        const registerFeedback = document.getElementById('registerFeedback');
        const toastMessage = document.getElementById('toastMessage');
        const openAddPokemonModalButton = document.getElementById('openAddPokemonModal');
        const addPokemonModal = document.getElementById('addPokemonModal');
        const addPokemonForm = document.getElementById('addPokemonForm');
        const closeAddPokemonModalButton = document.getElementById('closeAddPokemonModal');
        const cancelAddPokemonModalButton = document.getElementById('cancelAddPokemonModal');
        const submitAddPokemonButton = document.getElementById('submitAddPokemonButton');
        const addPokemonError = document.getElementById('addPokemonError');
        const addPokemonImageInput = document.getElementById('addPokemonImageInput');
        const addPokemonImagePreview = document.getElementById('addPokemonImagePreview');
        const addPokemonImagePlaceholder = document.getElementById('addPokemonImagePlaceholder');

        let state = {
            q: pokedexConfig.initialFilters.q || '',
            types: [...(pokedexConfig.initialFilters.types || [])],
            generation: pokedexConfig.initialFilters.generation || '',
            status: pokedexConfig.initialFilters.status || 'all',
            sort: pokedexConfig.initialFilters.sort || 'id',
            offset: pokedexConfig.initialNextOffset || pokedexConfig.limit,
            total: pokedexConfig.initialTotal || 0,
            hasMore: Boolean(pokedexConfig.initialHasMore),
            loading: false,
        };

        let debounceTimer = null;
        let activeController = null;
        let requestSequence = 0;
        let currentDetailPokemon = null;
        let toastTimeout = null;

        function debounceRefresh() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => refreshResults(true), 300);
        }

        function readControls() {
            state.q = searchInput.value.trim();
            state.types = typeInputs.filter((input) => input.checked).map((input) => input.value);
            state.generation = generationFilter.value;
            state.sort = sortFilter.value;
        }

        function applyControls() {
            searchInput.value = state.q || '';
            generationFilter.value = state.generation || '';
            sortFilter.value = state.sort || 'id';
            typeInputs.forEach((input) => {
                input.checked = state.types.includes(input.value);
            });
            updateVisualState();
        }

        function updateVisualState() {
            clearSearchButton.classList.toggle('hidden', searchInput.value.trim() === '');
            typeButtons.forEach((button) => {
                const input = button.querySelector('input');
                button.classList.toggle('is-active', input.checked);
            });
            statusButtons.forEach((button) => {
                button.classList.toggle('is-active', button.dataset.statusFilter === state.status);
            });
        }

        function buildParams(offset) {
            const params = new URLSearchParams();
            params.set('limit', pokedexConfig.limit);
            params.set('offset', offset);

            if (state.q) params.set('q', state.q);
            if (state.types.length) params.set('types', state.types.join(','));
            if (state.generation) params.set('generation', state.generation);
            if (state.status !== 'all') params.set('status', state.status);
            if (state.sort !== 'id') params.set('sort', state.sort);

            return params;
        }

        function updateUrl() {
            const params = buildParams(0);
            params.delete('limit');
            params.delete('offset');

            const query = params.toString();
            const nextUrl = query ? `${window.location.pathname}?${query}` : window.location.pathname;
            window.history.pushState({ filters: { ...state } }, '', nextUrl);
        }

        function filtersFromUrl() {
            const params = new URLSearchParams(window.location.search);

            return {
                q: params.get('q') || '',
                types: (params.get('types') || '').split(',').filter(Boolean),
                generation: params.get('generation') || '',
                status: params.get('status') || 'all',
                sort: params.get('sort') || 'id',
            };
        }

        function skeletonMarkup(count = 8) {
            return Array.from({ length: count }).map(() => `
                <article class="skeleton-card border border-zinc-800 bg-zinc-900/40"></article>
            `).join('');
        }

        function setLoading(loading, append = false) {
            state.loading = loading;
            loadMoreButton.disabled = loading;

            if (loading && append) {
                grid.insertAdjacentHTML('beforeend', `<div data-skeleton-row class="contents">${skeletonMarkup(4)}</div>`);
            }

            if (loading && !append) {
                grid.innerHTML = skeletonMarkup(8);
            }
        }

        function removeSkeletons() {
            grid.querySelectorAll('[data-skeleton-row]').forEach((node) => node.remove());
        }

        function setWarning(message) {
            apiWarning.textContent = message || '';
            apiWarning.classList.toggle('hidden', !message);
        }

        function showToast(message, type = 'success') {
            clearTimeout(toastTimeout);
            toastMessage.textContent = message;
            toastMessage.className = `fixed right-4 top-4 z-[60] max-w-sm rounded-xl px-5 py-4 text-sm font-black text-white shadow-2xl backdrop-blur-md ${type === 'success' ? 'bg-emerald-600/90' : 'bg-red-600/90'}`;
            toastMessage.classList.remove('hidden');
            toastTimeout = setTimeout(() => {
                toastMessage.classList.add('hidden');
            }, 3500);
        }

        function setRegisterFeedback(message = '', type = 'success') {
            const target = document.getElementById('registerFeedback');
            if (!target) return;
            target.textContent = message;
            target.className = `mt-3 rounded-lg px-3 py-2 text-center text-sm font-bold ${type === 'success' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'}`;
            target.classList.toggle('hidden', !message);
        }

        function setRegisterButtonState(pokemon, loading = false) {
            if (!pokemon) {
                registerPokemonButton.disabled = true;
                registerPokemonButton.textContent = 'Pokémon Indisponível';
                return;
            }

            if (loading) {
                registerPokemonButton.disabled = true;
                registerPokemonButton.textContent = 'Salvando...';
                registerPokemonButton.className = 'w-full py-4 rounded-2xl bg-zinc-800 font-black text-sm text-zinc-500 shadow-xl disabled:cursor-wait';
                return;
            }

            if (pokemon.is_registered) {
                registerPokemonButton.disabled = true;
                registerPokemonButton.textContent = '\u2714 Já Capturado';
                registerPokemonButton.className = 'w-full py-4 rounded-2xl bg-emerald-600/20 font-black text-sm text-emerald-500 ring-1 ring-emerald-500/30 disabled:cursor-not-allowed shadow-lg';
                return;
            }

            registerPokemonButton.disabled = false;
            registerPokemonButton.textContent = '\u271A Capturar Pokémon';
            registerPokemonButton.className = 'w-full py-4 rounded-2xl bg-red-600 font-black text-sm text-white shadow-[0_0_20px_rgba(220,38,38,0.3)] transition-all hover:bg-red-500 active:scale-95 disabled:cursor-not-allowed';
        }

        function setDeleteButtonState(pokemon, loading = false) {
            deletePokemonButton.disabled = loading;
            deletePokemonButton.textContent = loading ? 'Excluindo...' : 'Liberar Pokémon';
            deletePokemonButton.className = `${pokemon?.is_registered ? '' : 'hidden '}w-full py-4 rounded-2xl border border-zinc-800 bg-zinc-950/50 font-black text-[10px] uppercase tracking-widest text-zinc-500 transition-all hover:bg-red-600/10 hover:text-red-500`;
        }

        function updateCounters(data) {
            const loaded = grid.querySelectorAll('[data-pokemon-card]').length;
            const total = Number(data.total || 0);

            resultCounter.textContent = total === 1 ? 'Total: 1' : `Total: ${total}`;
            loadedCounter.textContent = `${loaded} ativos`;
            emptyState.classList.toggle('hidden', total !== 0);
            loadMoreButton.classList.toggle('hidden', !state.hasMore);

            Object.entries(data.status_counts || {}).forEach(([key, value]) => {
                const target = document.querySelector(`[data-status-count="${key}"]`);
                if (target) {
                    target.textContent = `(${value})`;
                }
            });
        }

        async function fetchResults({ append = false, pushUrl = false } = {}) {
            if (state.loading && append) {
                return;
            }

            const offset = append ? state.offset : 0;
            const requestId = ++requestSequence;

            if (!append && activeController) {
                activeController.abort();
            }

            activeController = new AbortController();
            setLoading(true, append);

            try {
                const response = await fetch(`${pokedexConfig.buscarUrl}?${buildParams(offset).toString()}`, {
                    headers: { Accept: 'application/json' },
                    signal: activeController.signal,
                });
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Erro ao carregar Pokemon.');
                }

                if (requestId !== requestSequence) {
                    return;
                }

                removeSkeletons();

                if (append) {
                    grid.insertAdjacentHTML('beforeend', data.html);
                } else {
                    grid.innerHTML = data.html;
                }

                state.offset = data.next_offset;
                state.total = data.total;
                state.hasMore = Boolean(data.has_more);
                setWarning(data.warning);
                updateCounters(data);

                if (pushUrl) {
                    updateUrl();
                }
            } catch (error) {
                if (requestId === requestSequence && error.name !== 'AbortError') {
                    removeSkeletons();
                    if (!append) {
                        grid.innerHTML = '';
                    }
                    setWarning(error.message || 'Erro ao carregar Pokemon.');
                }
            } finally {
                if (requestId === requestSequence) {
                    state.loading = false;
                    loadMoreButton.disabled = false;
                }
            }
        }

        function refreshResults(pushUrl = false) {
            readControls();
            updateVisualState();
            state.offset = 0;
            state.hasMore = false;
            fetchResults({ append: false, pushUrl });
        }

        function loadMore() {
            if (state.loading || !state.hasMore) {
                return;
            }

            fetchResults({ append: true, pushUrl: false });
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            }[char]));
        }

        function typeBadge(type) {
            const color = pokedexConfig.typeColors[type] || pokedexConfig.typeColors.normal;
            return `<span class="rounded-lg border border-zinc-800 bg-zinc-950/50 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-white/90 shadow-sm ring-1 ring-zinc-800" style="border-color: ${color}44">${escapeHtml(type)}</span>`;
        }

        function renderStats(stats) {
            const lines = (stats || []).map((stat) => {
                const val = Math.min(255, Number(stat.value));
                const percent = (val / 255) * 100;

                return `
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500 group-hover:text-red-500 transition-colors">${escapeHtml(stat.label || stat.name)}</span>
                            <span class="text-xs font-black text-white">${val}</span>
                        </div>
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-zinc-950/50 ring-1 ring-zinc-800">
                            <div 
                                class="h-full rounded-full bg-gradient-to-r from-red-600 to-amber-500 shadow-[0_0_10px_rgba(220,38,38,0.4)] transition-all duration-1000 ease-out" 
                                style="width: 0%"
                                data-stat-bar="${percent}"
                            ></div>
                        </div>
                    </div>
                `;
            }).join('');

            const container = document.getElementById('detailStats');
            container.innerHTML = lines || '<p class="text-sm font-semibold text-zinc-500">Sem stats disponiveis.</p>';
            
            setTimeout(() => {
                container.querySelectorAll('[data-stat-bar]').forEach(bar => {
                    bar.style.width = `${bar.dataset.statBar}%`;
                });
            }, 100);
        }

        async function openDetails(url) {
            try {
                const response = await fetch(url, { headers: { Accept: 'application/json' } });
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Detalhes indisponiveis.');
                }

                const pokemon = data.pokemon;
                currentDetailPokemon = pokemon;
                document.getElementById('detailNumber').textContent = pokemon.number;
                document.getElementById('detailName').textContent = pokemon.name;
                document.getElementById('detailImage').src = pokemon.image;
                document.getElementById('detailImage').alt = pokemon.name;
                document.getElementById('detailTypes').innerHTML = (pokemon.types || []).map(typeBadge).join('');
                document.getElementById('detailHeight').textContent = pokemon.height ? `${pokemon.height} m` : '-';
                document.getElementById('detailWeight').textContent = pokemon.weight ? `${pokemon.weight} kg` : '-';
                
                const abilitiesContainer = document.getElementById('detailAbilities');
                abilitiesContainer.innerHTML = (pokemon.abilities || []).map(ability => 
                    `<span class="rounded-lg bg-zinc-800 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-zinc-300 ring-1 ring-zinc-700">${escapeHtml(ability)}</span>`
                ).join('');

                const flavorContainer = document.getElementById('detailFlavorContainer');
                const flavorText = document.getElementById('detailFlavor');
                if (pokemon.flavor_text) {
                    flavorText.textContent = pokemon.flavor_text;
                    flavorContainer.classList.remove('hidden');
                } else {
                    flavorContainer.classList.add('hidden');
                }

                const headerBg = document.getElementById('detailHeaderBg');
                const primaryType = pokemon.types?.[0] || 'normal';
                const typeColor = pokedexConfig.typeColors[primaryType] || '#64748b';
                headerBg.style.backgroundColor = typeColor;

                renderStats(pokemon.stats);
                setRegisterFeedback();
                setRegisterButtonState(pokemon);
                setDeleteButtonState(pokemon);

                detailModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } catch (error) {
                setWarning(error.message || 'Detalhes indisponiveis.');
            }
        }

        function getResponseMessage(data) {
            if (data.message) {
                return data.message;
            }

            if (data.errors) {
                const firstError = Object.values(data.errors)[0];
                if (Array.isArray(firstError) && firstError.length) {
                    return firstError[0];
                }
            }

            return 'Erro ao salvar. Verifique os dados e tente novamente.';
        }

        function openAddPokemonModal() {
            addPokemonError.textContent = '';
            addPokemonError.classList.add('hidden');
            addPokemonModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeAddPokemonModal() {
            addPokemonModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function resetAddPokemonForm() {
            addPokemonForm.reset();
            addPokemonImagePreview.src = '';
            addPokemonImagePreview.classList.add('hidden');
            addPokemonImagePlaceholder.classList.remove('hidden');
            addPokemonError.textContent = '';
            addPokemonError.classList.add('hidden');
        }

        async function submitAddPokemon(event) {
            event.preventDefault();

            addPokemonError.textContent = '';
            addPokemonError.classList.add('hidden');
            submitAddPokemonButton.disabled = true;
            submitAddPokemonButton.textContent = 'Salvando...';

            try {
                const response = await fetch(pokedexConfig.cadastrarUrl, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': pokedexConfig.csrfToken,
                    },
                    body: new FormData(addPokemonForm),
                });
                const data = await response.json().catch(() => ({}));

                if (!response.ok || !data.success) {
                    throw new Error(getResponseMessage(data));
                }

                resetAddPokemonForm();
                closeAddPokemonModal();
                showToast(data.message || 'Pok\u00E9mon cadastrado com sucesso!', 'success');
                refreshResults(false);
            } catch (error) {
                const message = error.message || 'Erro ao salvar. Verifique os dados e tente novamente.';
                addPokemonError.textContent = message;
                addPokemonError.classList.remove('hidden');
                showToast(message, 'error');
            } finally {
                submitAddPokemonButton.disabled = false;
                submitAddPokemonButton.textContent = 'Salvar Pok\u00E9mon';
            }
        }

        async function registerCurrentPokemon() {
            if (!currentDetailPokemon || currentDetailPokemon.is_registered) {
                return;
            }

            setRegisterFeedback();
            setRegisterButtonState(currentDetailPokemon, true);

            try {
                const response = await fetch(pokedexConfig.cadastrarUrl, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': pokedexConfig.csrfToken,
                    },
                    body: JSON.stringify({
                        pokemon_id: currentDetailPokemon.id,
                    }),
                });
                const data = await response.json().catch(() => ({}));

                if (!response.ok || !data.success) {
                    throw new Error(getResponseMessage(data));
                }

                currentDetailPokemon.is_registered = true;
                setRegisterButtonState(currentDetailPokemon);
                setDeleteButtonState(currentDetailPokemon);
                showToast(data.message || 'Pok\u00E9mon capturado!', 'success');
                refreshResults(false);
            } catch (error) {
                const message = error.message || 'Erro ao capturar.';
                setRegisterFeedback(message, 'error');
                showToast(message, 'error');
                setRegisterButtonState(currentDetailPokemon);
            }
        }

        async function deleteCurrentPokemon() {
            if (!currentDetailPokemon || !currentDetailPokemon.is_registered) {
                return;
            }

            if (!confirm(`Deseja realmente liberar ${currentDetailPokemon.name}?`)) {
                return;
            }

            setDeleteButtonState(currentDetailPokemon, true);

            try {
                const deleteUrl = currentDetailPokemon.delete_url || `/pokemon/${currentDetailPokemon.id}/excluir`;
                const response = await fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': pokedexConfig.csrfToken,
                    },
                });
                const data = await response.json().catch(() => ({}));

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Erro ao excluir.');
                }

                currentDetailPokemon.is_registered = false;
                setRegisterButtonState(currentDetailPokemon);
                setDeleteButtonState(currentDetailPokemon);
                showToast(data.message || 'Pok\u00E9mon liberado!', 'success');
                refreshResults(false);
            } catch (error) {
                showToast(error.message, 'error');
                setDeleteButtonState(currentDetailPokemon);
            }
        }

        searchInput.addEventListener('input', debounceRefresh);
        clearSearchButton.addEventListener('click', () => {
            searchInput.value = '';
            refreshResults(true);
        });
        clearFiltersButton.addEventListener('click', () => {
            searchInput.value = '';
            typeInputs.forEach((input) => (input.checked = false));
            generationFilter.value = '';
            sortFilter.value = 'id';
            refreshResults(true);
        });
        generationFilter.addEventListener('change', () => refreshResults(true));
        sortFilter.addEventListener('change', () => refreshResults(true));
        typeInputs.forEach((input) => input.addEventListener('change', () => refreshResults(true)));

        statusButtons.forEach((button) => {
            button.addEventListener('click', () => {
                state.status = button.dataset.statusFilter;
                refreshResults(true);
            });
        });

        loadMoreButton.addEventListener('click', loadMore);

        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && state.hasMore && !state.loading) {
                loadMore();
            }
        }, { rootMargin: '200px' });
        observer.observe(sentinel);

        grid.addEventListener('click', (event) => {
            const card = event.target.closest('[data-pokemon-card]');
            if (card) {
                openDetails(card.dataset.detailUrl);
            }
        });

        closeDetailModal.addEventListener('click', () => {
            detailModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        detailModal.addEventListener('click', (event) => {
            if (event.target === detailModal) {
                closeDetailModal.click();
            }
        });

        registerPokemonButton.addEventListener('click', registerCurrentPokemon);
        deletePokemonButton.addEventListener('click', deleteCurrentPokemon);

        openAddPokemonModalButton.addEventListener('click', openAddPokemonModal);
        closeAddPokemonModalButton.addEventListener('click', closeAddPokemonModal);
        cancelAddPokemonModalButton.addEventListener('click', closeAddPokemonModal);
        addPokemonModal.addEventListener('click', (event) => {
            if (event.target === addPokemonModal) {
                closeAddPokemonModal();
            }
        });

        addPokemonImageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    addPokemonImagePreview.src = e.target.result;
                    addPokemonImagePreview.classList.remove('hidden');
                    addPokemonImagePlaceholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        addPokemonForm.addEventListener('submit', submitAddPokemon);

        window.addEventListener('popstate', (event) => {
            const filters = event.state?.filters || filtersFromUrl();
            state = { ...state, ...filters };
            applyControls();
            refreshResults(false);
        });

        applyControls();
    </script>
</body>
</html>
