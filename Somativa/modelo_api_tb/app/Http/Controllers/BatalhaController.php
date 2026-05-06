<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class BatalhaController extends Controller
{
    private const API_BASE = 'https://pokeapi.co/api/v2';
    private const MAX_POKEMON_ID = 1025;
    private const SESSION_KEY = 'pokemon_battle';

    private const LEADERS = [
        [
            'name' => 'Brock',
            'type' => 'Pedra',
            'level' => 12,
            'team' => ['geodude', 'onix'],
        ],
        [
            'name' => 'Misty',
            'type' => 'Agua',
            'level' => 18,
            'team' => ['staryu', 'starmie'],
        ],
        [
            'name' => 'Lt. Surge',
            'type' => 'Eletrico',
            'level' => 24,
            'team' => ['voltorb', 'pikachu', 'raichu'],
        ],
        [
            'name' => 'Erika',
            'type' => 'Planta',
            'level' => 29,
            'team' => ['victreebel', 'tangela', 'vileplume'],
        ],
        [
            'name' => 'Koga',
            'type' => 'Veneno',
            'level' => 37,
            'team' => ['koffing', 'muk', 'gengar'],
        ],
        [
            'name' => 'Sabrina',
            'type' => 'Psiquico',
            'level' => 43,
            'team' => ['kadabra', 'mr-mime', 'alakazam'],
        ],
        [
            'name' => 'Blaine',
            'type' => 'Fogo',
            'level' => 47,
            'team' => ['growlithe', 'arcanine', 'rapidash'],
        ],
        [
            'name' => 'Giovanni',
            'type' => 'Terra',
            'level' => 50,
            'team' => ['rhyhorn', 'dugtrio', 'nidoking'],
        ],
        [
            'name' => 'Elite 4',
            'type' => 'Misto',
            'level' => 55,
            'team' => ['lapras', 'dragonair', 'machamp', 'gengar', 'alakazam'],
        ],
        [
            'name' => 'Campeao',
            'type' => 'Boss Final',
            'level' => 65,
            'team' => ['articuno', 'zapdos', 'moltres', 'mewtwo', 'lugia', 'ho-oh'],
        ],
    ];

    private const FALLBACK_IDS = [
        'geodude' => 74,
        'onix' => 95,
        'staryu' => 120,
        'starmie' => 121,
        'voltorb' => 100,
        'pikachu' => 25,
        'raichu' => 26,
        'victreebel' => 71,
        'tangela' => 114,
        'vileplume' => 45,
        'koffing' => 109,
        'muk' => 89,
        'gengar' => 94,
        'kadabra' => 64,
        'mr-mime' => 122,
        'alakazam' => 65,
        'growlithe' => 58,
        'arcanine' => 59,
        'rapidash' => 78,
        'rhyhorn' => 111,
        'dugtrio' => 51,
        'nidoking' => 34,
        'lapras' => 131,
        'dragonair' => 148,
        'machamp' => 68,
        'articuno' => 144,
        'zapdos' => 145,
        'moltres' => 146,
        'mewtwo' => 150,
        'lugia' => 249,
        'ho-oh' => 250,
    ];

    private const TYPE_CHART = [
        'normal' => ['double' => [], 'half' => ['rock', 'steel'], 'zero' => ['ghost']],
        'fire' => ['double' => ['grass', 'ice', 'bug', 'steel'], 'half' => ['fire', 'water', 'rock', 'dragon'], 'zero' => []],
        'water' => ['double' => ['fire', 'ground', 'rock'], 'half' => ['water', 'grass', 'dragon'], 'zero' => []],
        'electric' => ['double' => ['water', 'flying'], 'half' => ['electric', 'grass', 'dragon'], 'zero' => ['ground']],
        'grass' => ['double' => ['water', 'ground', 'rock'], 'half' => ['fire', 'grass', 'poison', 'flying', 'bug', 'dragon', 'steel'], 'zero' => []],
        'ice' => ['double' => ['grass', 'ground', 'flying', 'dragon'], 'half' => ['fire', 'water', 'ice', 'steel'], 'zero' => []],
        'fighting' => ['double' => ['normal', 'ice', 'rock', 'dark', 'steel'], 'half' => ['poison', 'flying', 'psychic', 'bug', 'fairy'], 'zero' => ['ghost']],
        'poison' => ['double' => ['grass', 'fairy'], 'half' => ['poison', 'ground', 'rock', 'ghost'], 'zero' => ['steel']],
        'ground' => ['double' => ['fire', 'electric', 'poison', 'rock', 'steel'], 'half' => ['grass', 'bug'], 'zero' => ['flying']],
        'flying' => ['double' => ['grass', 'fighting', 'bug'], 'half' => ['electric', 'rock', 'steel'], 'zero' => []],
        'psychic' => ['double' => ['fighting', 'poison'], 'half' => ['psychic', 'steel'], 'zero' => ['dark']],
        'bug' => ['double' => ['grass', 'psychic', 'dark'], 'half' => ['fire', 'fighting', 'poison', 'flying', 'ghost', 'steel', 'fairy'], 'zero' => []],
        'rock' => ['double' => ['fire', 'ice', 'flying', 'bug'], 'half' => ['fighting', 'ground', 'steel'], 'zero' => []],
        'ghost' => ['double' => ['psychic', 'ghost'], 'half' => ['dark'], 'zero' => ['normal']],
        'dragon' => ['double' => ['dragon'], 'half' => ['steel'], 'zero' => ['fairy']],
        'dark' => ['double' => ['psychic', 'ghost'], 'half' => ['fighting', 'dark', 'fairy'], 'zero' => []],
        'steel' => ['double' => ['ice', 'rock', 'fairy'], 'half' => ['fire', 'water', 'electric', 'steel'], 'zero' => []],
        'fairy' => ['double' => ['fighting', 'dragon', 'dark'], 'half' => ['fire', 'poison', 'steel'], 'zero' => []],
    ];

    private const FALLBACK_MOVES = [
        ['name' => 'tackle', 'label' => 'Tackle', 'type' => 'normal', 'power' => 40, 'pp' => 35],
        ['name' => 'quick-attack', 'label' => 'Quick Attack', 'type' => 'normal', 'power' => 40, 'pp' => 30],
        ['name' => 'swift', 'label' => 'Swift', 'type' => 'normal', 'power' => 60, 'pp' => 20],
        ['name' => 'headbutt', 'label' => 'Headbutt', 'type' => 'normal', 'power' => 70, 'pp' => 15],
    ];

    public function index()
    {
        $battle = session(self::SESSION_KEY);

        if ($battle) {
            return view('batalha', [
                'mode' => 'battle',
                'battle' => $this->clientState($battle),
                'leaders' => self::LEADERS,
                'availablePokemon' => [],
            ]);
        }

        return view('batalha', [
            'mode' => 'selection',
            'battle' => null,
            'leaders' => self::LEADERS,
            'availablePokemon' => $this->availablePokemon(),
        ]);
    }

    public function iniciar(Request $request)
    {
        $data = $request->validate([
            'pokemon' => ['required', 'array', 'min:3', 'max:6'],
            'pokemon.*' => ['required', 'integer', 'min:1'],
        ], [
            'pokemon.required' => 'Escolha entre 3 e 6 Pokemon para iniciar.',
            'pokemon.min' => 'Escolha pelo menos 3 Pokemon.',
            'pokemon.max' => 'Escolha no maximo 6 Pokemon.',
        ]);

        $selected = collect($data['pokemon'])->map(fn ($id) => (int) $id)->unique()->take(6)->values();
        $team = $selected
            ->map(fn (int $id) => $this->battlePokemon($id, 50, 'player'))
            ->filter()
            ->values()
            ->all();

        if (count($team) < 3) {
            return back()->withErrors(['pokemon' => 'Nao foi possivel carregar pelo menos 3 Pokemon.']);
        }

        $battle = [
            'leader_index' => 0,
            'player_team' => $team,
            'enemy_team' => $this->leaderTeam(0),
            'active_player' => 0,
            'active_enemy' => 0,
            'status' => 'battle',
            'messages' => ['Brock quer batalhar!'],
            'turn' => 1,
        ];

        session([self::SESSION_KEY => $battle]);

        return redirect()->route('batalha.index');
    }

    public function turno(Request $request)
    {
        $battle = session(self::SESSION_KEY);

        if (! $battle) {
            return response()->json(['message' => 'Nenhuma batalha ativa.'], 422);
        }

        if (($battle['status'] ?? 'battle') !== 'battle') {
            return response()->json(['battle' => $this->clientState($battle)]);
        }

        $action = $request->input('action', 'move');
        $messages = [];

        if ($action === 'move') {
            $battle = $this->processMoveTurn($battle, (int) $request->integer('move_index', 0), $messages);
        } elseif ($action === 'switch') {
            $battle = $this->processSwitchTurn($battle, (int) $request->integer('switch_index', 0), $messages);
        } elseif ($action === 'bag') {
            $messages[] = 'A mochila esta vazia!';
        } elseif ($action === 'run') {
            $messages[] = 'Nao da para fugir de uma batalha de lider!';
        } else {
            $messages[] = 'Escolha uma acao valida.';
        }

        $battle['messages'] = $messages;
        $battle['turn'] = ($battle['turn'] ?? 1) + 1;
        session([self::SESSION_KEY => $battle]);

        return response()->json(['battle' => $this->clientState($battle)]);
    }

    public function proximo(Request $request)
    {
        $battle = session(self::SESSION_KEY);

        if (! $battle) {
            return $this->proximoResponse($request, 'Nenhuma batalha ativa.');
        }

        if (($battle['status'] ?? null) === 'champion_victory') {
            session()->forget(self::SESSION_KEY);

            return $this->proximoResponse($request, 'Voce ja venceu a Liga Pokemon!');
        }

        if (($battle['status'] ?? null) !== 'leader_victory') {
            return $this->proximoResponse($request, 'Venca o lider atual antes de avancar.');
        }

        $defeatedLeader = (int) $battle['leader_index'];
        $nextLeader = $defeatedLeader + 1;
        $healPercent = in_array($defeatedLeader + 1, [5, 10], true) ? 1.0 : 0.5;
        $battle['player_team'] = $this->healTeam($battle['player_team'], $healPercent);

        if (! isset(self::LEADERS[$nextLeader])) {
            $battle['status'] = 'champion_victory';
            $battle['messages'] = ['Voce venceu todos os lideres!'];
            session([self::SESSION_KEY => $battle]);

            return $this->proximoResponse($request, 'Campeao derrotado!');
        }

        $battle['leader_index'] = $nextLeader;
        $battle['enemy_team'] = $this->leaderTeam($nextLeader);
        $battle['active_player'] = $this->firstAliveIndex($battle['player_team']) ?? 0;
        $battle['active_enemy'] = 0;
        $battle['status'] = 'battle';
        $battle['messages'] = [self::LEADERS[$nextLeader]['name'] . ' quer batalhar!'];
        session([self::SESSION_KEY => $battle]);

        return $this->proximoResponse($request, 'Proximo lider carregado.');
    }

    public function resetar(Request $request)
    {
        if ($request->boolean('atual') && session()->has(self::SESSION_KEY)) {
            $battle = session(self::SESSION_KEY);
            $leaderIndex = (int) ($battle['leader_index'] ?? 0);
            $battle['player_team'] = $this->healTeam($battle['player_team'] ?? [], 1.0);
            $battle['enemy_team'] = $this->leaderTeam($leaderIndex);
            $battle['active_player'] = $this->firstAliveIndex($battle['player_team']) ?? 0;
            $battle['active_enemy'] = 0;
            $battle['status'] = 'battle';
            $battle['messages'] = ['Revanche contra ' . self::LEADERS[$leaderIndex]['name'] . '!'];
            session([self::SESSION_KEY => $battle]);

            return redirect()->route('batalha.index');
        }

        session()->forget(self::SESSION_KEY);

        return redirect()->route('batalha.index');
    }

    private function proximoResponse(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'battle' => session(self::SESSION_KEY) ? $this->clientState(session(self::SESSION_KEY)) : null,
            ]);
        }

        return redirect()->route('batalha.index');
    }

    private function availablePokemon(): array
    {
        $apiList = $this->apiPokemonList();
        $local = Pokemon::query()
            ->select(['pokemon_id', 'nome', 'tipo_primario', 'tipo_secundario', 'hp', 'imagem_local', 'imagem_url'])
            ->orderBy('pokemon_id')
            ->get()
            ->keyBy('pokemon_id');

        $items = $apiList->map(function (array $pokemon) use ($local) {
            $id = (int) $pokemon['id'];
            $localPokemon = $local->get($id);

            return [
                'id' => $id,
                'name' => $this->displayName($localPokemon?->nome ?: $pokemon['name']),
                'image' => $this->pokemonImageUrl($id, $localPokemon),
                'types' => $localPokemon ? $this->localTypes($localPokemon) : [],
                'hp' => $localPokemon?->hp,
                'local' => (bool) $localPokemon,
            ];
        })->keyBy('id');

        foreach ($local as $pokemon) {
            $id = (int) $pokemon->pokemon_id;

            if ($items->has($id)) {
                continue;
            }

            $items->put($id, [
                'id' => $id,
                'name' => $this->displayName($pokemon->nome),
                'image' => $this->pokemonImageUrl($id, $pokemon),
                'types' => $this->localTypes($pokemon),
                'hp' => (int) $pokemon->hp,
                'local' => true,
            ]);
        }

        return $items->sortKeys()->values()->all();
    }

    private function apiPokemonList(): Collection
    {
        try {
            $cached = Cache::get('battle:pokemon-list:v1');

            if (is_array($cached)) {
                return collect($cached);
            }

            if ($this->apiUnavailable()) {
                return $this->fallbackApiPokemonList();
            }

            $response = Http::timeout(5)->get(self::API_BASE . '/pokemon', [
                'limit' => self::MAX_POKEMON_ID,
                'offset' => 0,
            ]);

            if (! $response->successful()) {
                $this->markApiUnavailable();

                return $this->fallbackApiPokemonList();
            }

            $items = collect($response->json('results', []))
                ->map(function (array $pokemon) {
                    $id = $this->extractPokemonId($pokemon['url'] ?? '');

                    return $id ? ['id' => $id, 'name' => $pokemon['name'] ?? ('pokemon-' . $id)] : null;
                })
                ->filter()
                ->values()
                ->all();

            Cache::put('battle:pokemon-list:v1', $items, now()->addHours(24));
            $this->markApiAvailable();

            return collect($items);
        } catch (Throwable) {
            $this->markApiUnavailable();

            return $this->fallbackApiPokemonList();
        }
    }

    private function fallbackApiPokemonList(): Collection
    {
        return collect(range(1, 151))
            ->map(fn (int $id) => [
                'id' => $id,
                'name' => 'pokemon-' . $id,
            ]);
    }

    private function processMoveTurn(array $battle, int $moveIndex, array &$messages): array
    {
        $playerSpeed = $this->activePlayer($battle)['stats']['speed'] ?? 1;
        $enemySpeed = $this->activeEnemy($battle)['stats']['speed'] ?? 1;
        $playerFirst = $playerSpeed === $enemySpeed ? (bool) random_int(0, 1) : $playerSpeed > $enemySpeed;

        $steps = $playerFirst ? ['player', 'enemy'] : ['enemy', 'player'];

        foreach ($steps as $actor) {
            if (($battle['status'] ?? 'battle') !== 'battle') {
                break;
            }

            if ($actor === 'player') {
                $battle = $this->executeAttack($battle, 'player', $moveIndex, $messages);
            } else {
                $enemyMove = $this->randomAvailableMoveIndex($this->activeEnemy($battle));
                $battle = $this->executeAttack($battle, 'enemy', $enemyMove, $messages);
            }

            $battle = $this->resolveFainting($battle, $messages);
        }

        return $battle;
    }

    private function processSwitchTurn(array $battle, int $switchIndex, array &$messages): array
    {
        if (! isset($battle['player_team'][$switchIndex])) {
            $messages[] = 'Esse Pokemon nao existe no seu time.';

            return $battle;
        }

        if (($battle['player_team'][$switchIndex]['current_hp'] ?? 0) <= 0) {
            $messages[] = $battle['player_team'][$switchIndex]['name'] . ' esta desmaiado.';

            return $battle;
        }

        if ($switchIndex === (int) $battle['active_player']) {
            $messages[] = $battle['player_team'][$switchIndex]['name'] . ' ja esta em campo.';

            return $battle;
        }

        $battle['active_player'] = $switchIndex;
        $messages[] = 'Vai, ' . $battle['player_team'][$switchIndex]['name'] . '!';
        $enemyMove = $this->randomAvailableMoveIndex($this->activeEnemy($battle));
        $battle = $this->executeAttack($battle, 'enemy', $enemyMove, $messages);

        return $this->resolveFainting($battle, $messages);
    }

    private function executeAttack(array $battle, string $side, int $moveIndex, array &$messages): array
    {
        $attackerIndex = $side === 'player' ? (int) $battle['active_player'] : (int) $battle['active_enemy'];
        $defenderIndex = $side === 'player' ? (int) $battle['active_enemy'] : (int) $battle['active_player'];
        $attackerTeamKey = $side === 'player' ? 'player_team' : 'enemy_team';
        $defenderTeamKey = $side === 'player' ? 'enemy_team' : 'player_team';

        $attacker = $battle[$attackerTeamKey][$attackerIndex];
        $defender = $battle[$defenderTeamKey][$defenderIndex];
        $move = $attacker['moves'][$moveIndex] ?? null;

        if (! $move) {
            $messages[] = $attacker['name'] . ' nao conhece esse golpe.';

            return $battle;
        }

        if (($move['pp'] ?? 0) <= 0) {
            $messages[] = $move['label'] . ' esta sem PP!';

            return $battle;
        }

        $battle[$attackerTeamKey][$attackerIndex]['moves'][$moveIndex]['pp']--;
        $damage = $this->calculateDamage($attacker, $defender, $move);
        $battle[$defenderTeamKey][$defenderIndex]['current_hp'] = max(0, (int) $defender['current_hp'] - $damage['damage']);

        $messages[] = $attacker['name'] . ' usou ' . $move['label'] . '!';

        if ($damage['effectiveness'] >= 2) {
            $messages[] = 'E super eficaz!';
        } elseif ($damage['effectiveness'] > 0 && $damage['effectiveness'] < 1) {
            $messages[] = 'Nao e muito eficaz...';
        } elseif ($damage['effectiveness'] == 0.0) {
            $messages[] = 'Nao afetou ' . $defender['name'] . '...';
        }

        if ($damage['damage'] > 0) {
            $messages[] = $defender['name'] . ' perdeu ' . $damage['damage'] . ' HP.';
        }

        return $battle;
    }

    private function resolveFainting(array $battle, array &$messages): array
    {
        $enemy = $this->activeEnemy($battle);
        $player = $this->activePlayer($battle);

        if (($enemy['current_hp'] ?? 0) <= 0) {
            $messages[] = $enemy['name'] . ' desmaiou!';
            $nextEnemy = $this->firstAliveIndex($battle['enemy_team']);

            if ($nextEnemy === null) {
                $leaderName = self::LEADERS[$battle['leader_index']]['name'];
                $messages[] = 'Voce venceu ' . $leaderName . '!';
                $battle['status'] = $battle['leader_index'] >= 9 ? 'champion_victory' : 'leader_victory';

                return $battle;
            }

            $battle['active_enemy'] = $nextEnemy;
            $messages[] = self::LEADERS[$battle['leader_index']]['name'] . ' enviou ' . $battle['enemy_team'][$nextEnemy]['name'] . '!';
        }

        if (($player['current_hp'] ?? 0) <= 0) {
            $messages[] = $player['name'] . ' desmaiou!';
            $nextPlayer = $this->firstAliveIndex($battle['player_team']);

            if ($nextPlayer === null) {
                $messages[] = 'Seu time inteiro desmaiou. Game Over!';
                $battle['status'] = 'game_over';

                return $battle;
            }

            $battle['active_player'] = $nextPlayer;
            $messages[] = 'Vai, ' . $battle['player_team'][$nextPlayer]['name'] . '!';
        }

        return $battle;
    }

    private function calculateDamage(array $attacker, array $defender, array $move): array
    {
        $power = max(20, (int) ($move['power'] ?? 40));
        $attack = max(1, (int) ($attacker['stats']['attack'] ?? 1));
        $defense = max(1, (int) ($defender['stats']['defense'] ?? 1));
        $level = max(1, (int) ($attacker['level'] ?? 50));
        $effectiveness = $this->typeEffectiveness($move['type'] ?? 'normal', $defender['types'] ?? ['normal']);
        $stab = in_array($move['type'] ?? 'normal', $attacker['types'] ?? [], true) ? 1.5 : 1.0;
        $random = random_int(85, 100) / 100;

        // Formula inspirada na original, com nivel suavizando o fator final.
        $base = (((2 * $level / 5 + 2) * $power * ($attack / $defense)) / 50 + 2);
        $damage = (int) floor($base * $effectiveness * $stab * $random);

        return [
            'damage' => max(0, $damage),
            'effectiveness' => $effectiveness,
        ];
    }

    private function typeEffectiveness(string $moveType, array $defenderTypes): float
    {
        $relations = $this->typeRelations($moveType);
        $modifier = 1.0;

        foreach ($defenderTypes as $type) {
            if (in_array($type, $relations['zero'], true)) {
                return 0.0;
            }

            if (in_array($type, $relations['double'], true)) {
                $modifier *= 2;
            }

            if (in_array($type, $relations['half'], true)) {
                $modifier *= 0.5;
            }
        }

        return $modifier;
    }

    private function typeRelations(string $type): array
    {
        $type = Str::lower($type);
        $cacheKey = "battle:type-relations:{$type}";

        try {
            $cached = Cache::get($cacheKey);

            if (is_array($cached)) {
                return $cached;
            }

            if ($this->apiUnavailable()) {
                return self::TYPE_CHART[$type] ?? self::TYPE_CHART['normal'];
            }

            $response = Http::timeout(3)->get(self::API_BASE . "/type/{$type}");

            if ($response->successful()) {
                $relations = [
                    'double' => collect($response->json('damage_relations.double_damage_to', []))->pluck('name')->all(),
                    'half' => collect($response->json('damage_relations.half_damage_to', []))->pluck('name')->all(),
                    'zero' => collect($response->json('damage_relations.no_damage_to', []))->pluck('name')->all(),
                ];
                Cache::put($cacheKey, $relations, now()->addHours(24));
                $this->markApiAvailable();

                return $relations;
            }

            $this->markApiUnavailable();
        } catch (Throwable) {
            $this->markApiUnavailable();
        }

        return self::TYPE_CHART[$type] ?? self::TYPE_CHART['normal'];
    }

    private function leaderTeam(int $leaderIndex): array
    {
        $leader = self::LEADERS[$leaderIndex];

        return collect($leader['team'])
            ->map(fn (string $name) => $this->battlePokemon($name, (int) $leader['level'], 'enemy'))
            ->filter()
            ->values()
            ->all();
    }

    private function battlePokemon(int|string $idOrName, int $level, string $side): ?array
    {
        $local = is_numeric($idOrName) ? Pokemon::where('pokemon_id', (int) $idOrName)->first() : null;
        $shouldUseApi = ! $local || (int) $local->pokemon_id <= self::MAX_POKEMON_ID;
        $detail = $shouldUseApi ? $this->pokemonDetail($idOrName) : null;

        if (! $detail && ! $local) {
            return null;
        }

        $id = (int) ($detail['id'] ?? $local?->pokemon_id ?? self::FALLBACK_IDS[(string) $idOrName] ?? 0);
        $name = $detail['name'] ?? $local?->nome ?? $this->displayName((string) $idOrName);
        $types = $detail['types'] ?? $this->localTypes($local);
        $baseStats = $detail['stats'] ?? $this->localBaseStats($local);
        $stats = $this->scaledStats($baseStats, $level, $side);
        $maxHp = $side === 'player'
            ? max(1, (int) ($baseStats['hp'] ?? $local?->hp ?? $stats['hp']))
            : max(1, (int) $stats['hp']);
        $moves = $detail['moves'] ?? $this->fallbackMovesForTypes($types);

        return [
            'id' => $id,
            'name' => $this->displayName($name),
            'types' => $types ?: ['normal'],
            'level' => $level,
            'max_hp' => $maxHp,
            'current_hp' => $maxHp,
            'stats' => $stats,
            'moves' => $moves,
            'sprite_front' => $detail['sprite_front'] ?? $this->pokemonImageUrl($id, $local),
            'sprite_back' => $detail['sprite_back'] ?? $detail['sprite_front'] ?? $this->pokemonImageUrl($id, $local),
        ];
    }

    private function pokemonDetail(int|string $idOrName): ?array
    {
        $cacheKey = 'battle:pokemon-detail:' . Str::lower((string) $idOrName);

        try {
            $cached = Cache::get($cacheKey);

            if (is_array($cached)) {
                return $cached;
            }

            if ($this->apiUnavailable()) {
                return $this->fallbackPokemonDetail((string) $idOrName);
            }

            $response = Http::timeout(4)->get(self::API_BASE . '/pokemon/' . rawurlencode((string) $idOrName));

            if (! $response->successful()) {
                $this->markApiUnavailable();

                return $this->fallbackPokemonDetail((string) $idOrName);
            }

            $pokemon = $response->json();
            $moves = $this->movesForPokemon($pokemon['moves'] ?? [], $pokemon['types'][0]['type']['name'] ?? 'normal');
            $detail = [
                'id' => (int) ($pokemon['id'] ?? 0),
                'name' => $pokemon['name'] ?? (string) $idOrName,
                'types' => collect($pokemon['types'] ?? [])->sortBy('slot')->pluck('type.name')->filter()->values()->all(),
                'stats' => $this->baseStatsFromApi($pokemon['stats'] ?? []),
                'moves' => $moves,
                'sprite_front' => $pokemon['sprites']['versions']['generation-v']['black-white']['animated']['front_default']
                    ?? $pokemon['sprites']['front_default']
                    ?? $this->officialSpriteUrl((int) ($pokemon['id'] ?? 0)),
                'sprite_back' => $pokemon['sprites']['versions']['generation-v']['black-white']['animated']['back_default']
                    ?? $pokemon['sprites']['back_default']
                    ?? $pokemon['sprites']['front_default']
                    ?? $this->officialSpriteUrl((int) ($pokemon['id'] ?? 0)),
            ];

            Cache::put($cacheKey, $detail, now()->addHours(24));
            Cache::put('battle:pokemon-detail:' . $detail['id'], $detail, now()->addHours(24));
            $this->markApiAvailable();

            return $detail;
        } catch (Throwable) {
            $this->markApiUnavailable();

            return $this->fallbackPokemonDetail((string) $idOrName);
        }
    }

    private function fallbackPokemonDetail(string $name): ?array
    {
        $id = is_numeric($name) ? (int) $name : (self::FALLBACK_IDS[$name] ?? 0);

        if ($id < 1) {
            return null;
        }

        return [
            'id' => $id,
            'name' => is_numeric($name) ? 'pokemon-' . $id : $name,
            'types' => ['normal'],
            'stats' => ['hp' => 70, 'attack' => 70, 'defense' => 70, 'speed' => 65],
            'moves' => self::FALLBACK_MOVES,
            'sprite_front' => $this->officialSpriteUrl($id),
            'sprite_back' => $this->officialBackSpriteUrl($id),
        ];
    }

    private function movesForPokemon(array $moves, string $primaryType): array
    {
        $selected = collect($moves)
            ->take(4)
            ->map(fn (array $move) => $move['move']['url'] ?? null)
            ->filter()
            ->map(fn (string $url) => $this->moveDetail($url))
            ->filter(fn (?array $move) => $move && ($move['power'] ?? 0) > 0 && ($move['pp'] ?? 0) > 0)
            ->take(4)
            ->values()
            ->all();

        if (count($selected) >= 4) {
            return $selected;
        }

        return collect($selected)
            ->merge($this->fallbackMovesForTypes([$primaryType]))
            ->unique('name')
            ->take(4)
            ->values()
            ->all();
    }

    private function moveDetail(string $url): ?array
    {
        $cacheKey = 'battle:move:' . md5($url);

        try {
            $cached = Cache::get($cacheKey);

            if (is_array($cached)) {
                return $cached;
            }

            if ($this->apiUnavailable()) {
                return null;
            }

            $response = Http::timeout(3)->get($url);

            if (! $response->successful()) {
                $this->markApiUnavailable();

                return null;
            }

            $move = $response->json();
            $data = [
                'name' => $move['name'] ?? 'move',
                'label' => $this->displayName($move['name'] ?? 'move'),
                'type' => $move['type']['name'] ?? 'normal',
                'power' => (int) ($move['power'] ?? 40),
                'pp' => (int) ($move['pp'] ?? 20),
                'max_pp' => (int) ($move['pp'] ?? 20),
            ];

            Cache::put($cacheKey, $data, now()->addHours(24));
            $this->markApiAvailable();

            return $data;
        } catch (Throwable) {
            $this->markApiUnavailable();

            return null;
        }
    }

    private function fallbackMovesForTypes(array $types): array
    {
        $moves = self::FALLBACK_MOVES;
        $type = $types[0] ?? 'normal';
        $typedMove = [
            'name' => $type . '-strike',
            'label' => $this->displayName($type . ' strike'),
            'type' => $type,
            'power' => 65,
            'pp' => 20,
            'max_pp' => 20,
        ];

        array_unshift($moves, $typedMove);

        return collect($moves)
            ->map(fn (array $move) => $move + ['max_pp' => $move['pp']])
            ->take(4)
            ->values()
            ->all();
    }

    private function baseStatsFromApi(array $stats): array
    {
        return collect($stats)
            ->mapWithKeys(fn (array $stat) => [$stat['stat']['name'] ?? '' => (int) ($stat['base_stat'] ?? 1)])
            ->filter(fn ($value, $key) => $key !== '')
            ->pipe(fn (Collection $stats) => [
                'hp' => (int) ($stats['hp'] ?? 50),
                'attack' => (int) ($stats['attack'] ?? 50),
                'defense' => (int) ($stats['defense'] ?? 50),
                'speed' => (int) ($stats['speed'] ?? 50),
            ]);
    }

    private function localBaseStats(?Pokemon $pokemon): array
    {
        return [
            'hp' => (int) ($pokemon?->hp ?? 60),
            'attack' => (int) ($pokemon?->ataque ?? 60),
            'defense' => (int) ($pokemon?->defesa ?? 60),
            'speed' => (int) ($pokemon?->velocidade ?? 60),
        ];
    }

    private function scaledStats(array $baseStats, int $level, string $side): array
    {
        if ($side === 'player') {
            return [
                'hp' => max(1, (int) ($baseStats['hp'] ?? 50)),
                'attack' => max(1, (int) ($baseStats['attack'] ?? 50)),
                'defense' => max(1, (int) ($baseStats['defense'] ?? 50)),
                'speed' => max(1, (int) ($baseStats['speed'] ?? 50)),
            ];
        }

        return [
            'hp' => max(1, (int) floor(((2 * ($baseStats['hp'] ?? 50) * $level) / 100) + $level + 10)),
            'attack' => max(1, (int) floor(((2 * ($baseStats['attack'] ?? 50) * $level) / 100) + 5)),
            'defense' => max(1, (int) floor(((2 * ($baseStats['defense'] ?? 50) * $level) / 100) + 5)),
            'speed' => max(1, (int) floor(((2 * ($baseStats['speed'] ?? 50) * $level) / 100) + 5)),
        ];
    }

    private function clientState(array $battle): array
    {
        return [
            'leader_index' => $battle['leader_index'],
            'leader' => self::LEADERS[$battle['leader_index']],
            'player_team' => $battle['player_team'],
            'enemy_team' => $battle['enemy_team'],
            'active_player' => $battle['active_player'],
            'active_enemy' => $battle['active_enemy'],
            'player' => $this->activePlayer($battle),
            'enemy' => $this->activeEnemy($battle),
            'status' => $battle['status'],
            'messages' => $battle['messages'] ?? [],
        ];
    }

    private function activePlayer(array $battle): array
    {
        return $battle['player_team'][(int) $battle['active_player']] ?? $battle['player_team'][0];
    }

    private function activeEnemy(array $battle): array
    {
        return $battle['enemy_team'][(int) $battle['active_enemy']] ?? $battle['enemy_team'][0];
    }

    private function firstAliveIndex(array $team): ?int
    {
        foreach ($team as $index => $pokemon) {
            if (($pokemon['current_hp'] ?? 0) > 0) {
                return $index;
            }
        }

        return null;
    }

    private function randomAvailableMoveIndex(array $pokemon): int
    {
        $available = collect($pokemon['moves'] ?? [])
            ->filter(fn (array $move) => ($move['pp'] ?? 0) > 0)
            ->keys()
            ->values();

        if ($available->isEmpty()) {
            return 0;
        }

        return (int) $available->random();
    }

    private function healTeam(array $team, float $percent): array
    {
        return collect($team)
            ->map(function (array $pokemon) use ($percent) {
                $pokemon['current_hp'] = min(
                    (int) $pokemon['max_hp'],
                    (int) $pokemon['current_hp'] + (int) ceil($pokemon['max_hp'] * $percent)
                );

                foreach ($pokemon['moves'] as &$move) {
                    $move['pp'] = min((int) $move['max_pp'], (int) $move['pp'] + (int) ceil($move['max_pp'] * $percent));
                }

                return $pokemon;
            })
            ->values()
            ->all();
    }

    private function localTypes(?Pokemon $pokemon): array
    {
        return collect([$pokemon?->tipo_primario, $pokemon?->tipo_secundario])
            ->filter()
            ->map(fn ($type) => Str::lower((string) $type))
            ->values()
            ->all();
    }

    private function pokemonImageUrl(int $id, ?Pokemon $local = null): string
    {
        if ($local?->imagem_local) {
            return route('pokemon.imagem', ['path' => ltrim($local->imagem_local, '/')], false);
        }

        if ($local?->imagem_url) {
            return $local->imagem_url;
        }

        return $this->officialSpriteUrl($id);
    }

    private function officialSpriteUrl(int $id): string
    {
        return "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png";
    }

    private function officialBackSpriteUrl(int $id): string
    {
        return "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/back/{$id}.png";
    }

    private function extractPokemonId(string $url): ?int
    {
        if (! preg_match('~/pokemon/(\d+)/?$~', $url, $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    private function displayName(string $name): string
    {
        return Str::of($name)->replace('-', ' ')->title()->toString();
    }

    private function apiUnavailable(): bool
    {
        return (bool) Cache::get('battle:pokeapi-unavailable');
    }

    private function markApiUnavailable(): void
    {
        Cache::put('battle:pokeapi-unavailable', true, now()->addMinutes(5));
    }

    private function markApiAvailable(): void
    {
        Cache::forget('battle:pokeapi-unavailable');
    }
}
