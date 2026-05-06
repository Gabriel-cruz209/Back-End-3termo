<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PokedexController extends Controller
{
    private const API_BASE = 'https://pokeapi.co/api/v2';
    private const MAX_POKEMON_ID = 1025;
    private const DEFAULT_LIMIT = 48;
    private const CACHE_TTL_HOURS = 24;

    private const TYPES = [
        'normal', 'fire', 'water', 'electric', 'grass', 'ice', 'fighting', 'poison', 'ground',
        'flying', 'psychic', 'bug', 'rock', 'ghost', 'dragon', 'dark', 'steel', 'fairy',
    ];

    private const GENERATIONS = [
        1 => ['label' => 'Gen I', 'min' => 1, 'max' => 151],
        2 => ['label' => 'Gen II', 'min' => 152, 'max' => 251],
        3 => ['label' => 'Gen III', 'min' => 252, 'max' => 386],
        4 => ['label' => 'Gen IV', 'min' => 387, 'max' => 493],
        5 => ['label' => 'Gen V', 'min' => 494, 'max' => 649],
        6 => ['label' => 'Gen VI', 'min' => 650, 'max' => 721],
        7 => ['label' => 'Gen VII', 'min' => 722, 'max' => 809],
        8 => ['label' => 'Gen VIII', 'min' => 810, 'max' => 905],
        9 => ['label' => 'Gen IX', 'min' => 906, 'max' => 1025],
    ];

    public function index(Request $request)
    {
        $filters = $this->normalizeFilters($request);
        $registered = $this->registeredPokemon();
        $results = $this->resolveResults($filters, 0, self::DEFAULT_LIMIT, $registered);

        return view('pokedex', [
            'pokemons' => $results['pokemons'],
            'registeredIds' => $registered->keys()->map(fn ($id) => (int) $id)->values(),
            'filters' => $filters,
            'types' => self::TYPES,
            'generations' => self::GENERATIONS,
            'total' => $results['total'],
            'hasMore' => $results['has_more'],
            'nextOffset' => $results['next_offset'],
            'statusCounts' => $results['status_counts'],
            'warning' => $results['warning'],
        ]);
    }

    public function buscar(Request $request)
    {
        $filters = $this->normalizeFilters($request);
        $limit = min(max((int) $request->integer('limit', self::DEFAULT_LIMIT), 1), 96);
        $offset = max((int) $request->integer('offset', 0), 0);
        $registered = $this->registeredPokemon();
        $results = $this->resolveResults($filters, $offset, $limit, $registered);

        $html = collect($results['pokemons'])
            ->map(fn (array $pokemon) => view('components.pokemon-card', ['pokemon' => $pokemon])->render())
            ->implode('');

        return response()->json([
            'html' => $html,
            'pokemons' => $results['pokemons'],
            'total' => $results['total'],
            'shown' => min($offset + count($results['pokemons']), $results['total']),
            'has_more' => $results['has_more'],
            'next_offset' => $results['next_offset'],
            'status_counts' => $results['status_counts'],
            'warning' => $results['warning'],
        ]);
    }

    public function show(int $id)
    {
        if ($id < 1) {
            abort(404);
        }

        $registered = $this->registeredPokemon();
        $local = $registered->get($id);
        $details = $id <= self::MAX_POKEMON_ID ? $this->fetchPokemonDetail($id) : null;

        if (! $details && ! $local) {
            return response()->json([
                'message' => 'Pokemon nao encontrado.',
            ], 404);
        }

        return response()->json([
            'pokemon' => $this->buildDetailedPokemon($details, $local, $id),
        ]);
    }

    private function resolveResults(array $filters, int $offset, int $limit, Collection $registered): array
    {
        $warning = null;
        $apiList = $this->fetchPokemonList();

        if ($apiList->isEmpty()) {
            $warning = 'A PokeAPI esta indisponivel no momento. Exibindo apenas os Pokemon cadastrados localmente.';
            $baseList = $this->localBaseList($registered);
        } else {
            $baseList = $this->mergeLocalNames($apiList, $registered);
        }

        $filteredBeforeStatus = $this->applyFilters($baseList, $filters, $registered, false, $warning);
        $statusCounts = $this->statusCounts($filteredBeforeStatus, $registered);
        $filtered = $this->applyStatusFilter($filteredBeforeStatus, $filters['status'], $registered);
        $sorted = $this->sortPokemonList($filtered, $filters['sort']);
        $total = $sorted->count();
        $pageItems = $sorted->slice($offset, $limit)->values();
        $details = $apiList->isEmpty() ? collect() : $this->fetchPokemonDetailsBatch($pageItems->pluck('id')->all());

        $missingRemoteDetails = $pageItems
            ->filter(fn (array $item) => ! $registered->has((int) $item['id']) && ! $details->has((int) $item['id']))
            ->isNotEmpty();

        if ($missingRemoteDetails && $apiList->isNotEmpty()) {
            $warning = 'Alguns dados da PokeAPI nao puderam ser carregados agora. O cache e os registros locais foram usados quando possivel.';
        }

        $pokemons = $pageItems
            ->map(function (array $item) use ($details, $registered) {
                $id = (int) $item['id'];

                return $this->buildCardPokemon($details->get($id), $registered->get($id), $item);
            })
            ->filter()
            ->values()
            ->all();

        return [
            'pokemons' => $pokemons,
            'total' => $total,
            'has_more' => ($offset + $limit) < $total,
            'next_offset' => $offset + $limit,
            'status_counts' => $statusCounts,
            'warning' => $warning,
        ];
    }

    private function normalizeFilters(Request $request): array
    {
        $rawTypes = $request->query('types', []);

        if (is_string($rawTypes)) {
            $rawTypes = array_filter(explode(',', $rawTypes));
        }

        $types = collect((array) $rawTypes)
            ->map(fn ($type) => Str::lower(trim((string) $type)))
            ->filter(fn ($type) => in_array($type, self::TYPES, true))
            ->unique()
            ->values()
            ->all();

        $generation = $request->filled('generation') ? (int) $request->integer('generation') : null;
        $status = in_array($request->query('status'), ['all', 'registered', 'unregistered'], true)
            ? $request->query('status')
            : 'all';
        $sort = in_array($request->query('sort'), ['id', 'name_asc', 'name_desc'], true)
            ? $request->query('sort')
            : 'id';

        return [
            'q' => trim((string) $request->query('q', '')),
            'types' => $types,
            'generation' => array_key_exists($generation, self::GENERATIONS) ? $generation : null,
            'status' => $status,
            'sort' => $sort,
        ];
    }

    private function registeredPokemon(): Collection
    {
        try {
            return Pokemon::query()
                ->select([
                    'id',
                    'pokemon_id',
                    'nome',
                    'tipo_primario',
                    'tipo_secundario',
                    'hp',
                    'ataque',
                    'defesa',
                    'sp_ataque',
                    'sp_defesa',
                    'velocidade',
                    'altura',
                    'peso',
                    'imagem_url',
                    'imagem_local',
                    'flavor_text',
                    'apelido',
                ])
                ->orderBy('pokemon_id')
                ->get()
                ->keyBy('pokemon_id');
        } catch (Throwable $exception) {
            report($exception);

            return collect();
        }
    }

    private function fetchPokemonList(): Collection
    {
        if (app()->environment('testing')) {
            return collect();
        }

        if ($this->isApiTemporarilyUnavailable()) {
            return collect();
        }

        $cacheKey = 'pokedex:pokemon-list:v2:' . self::MAX_POKEMON_ID;

        try {
            $cached = Cache::get($cacheKey);

            if (is_array($cached)) {
                return collect($cached);
            }

            $response = Http::timeout(5)->get(self::API_BASE . '/pokemon', [
                'limit' => self::MAX_POKEMON_ID,
                'offset' => 0,
            ]);

            if (! $response->successful()) {
                $this->markApiUnavailable();

                return collect();
            }

            $items = collect($response->json('results', []))
                ->map(function (array $pokemon) {
                    $id = $this->extractPokemonId($pokemon['url'] ?? '');

                    if (! $id || $id > self::MAX_POKEMON_ID) {
                        return null;
                    }

                    return [
                        'id' => $id,
                        'name' => $pokemon['name'] ?? ('pokemon-' . $id),
                        'local_name' => null,
                    ];
                })
                ->filter()
                ->sortBy('id')
                ->values()
                ->all();

            Cache::put($cacheKey, $items, now()->addHours(self::CACHE_TTL_HOURS));
            $this->markApiAvailable();

            return collect($items);
        } catch (Throwable $exception) {
            report($exception);
            $this->markApiUnavailable();

            return collect();
        }
    }

    private function fetchPokemonDetail(int|string $idOrName): ?array
    {
        $details = $this->fetchPokemonDetailsBatch([$idOrName]);

        return $details->first();
    }

    private function fetchPokemonDetailsBatch(array $idsOrNames): Collection
    {
        $results = collect();
        $missing = [];

        foreach (array_unique($idsOrNames) as $idOrName) {
            $cacheKey = $this->detailCacheKey($idOrName);
            $cached = Cache::get($cacheKey);

            if ($cached) {
                $results->put((int) $cached['id'], $cached);
                continue;
            }

            $missing[] = $idOrName;
        }

        if ($missing === []) {
            return $results;
        }

        if ($this->isApiTemporarilyUnavailable()) {
            return $results;
        }

        try {
            $responses = Http::pool(function (Pool $pool) use ($missing) {
                return collect($missing)
                    ->map(fn ($idOrName) => $pool->timeout(5)->get(self::API_BASE . '/pokemon/' . rawurlencode((string) $idOrName)))
                    ->all();
            });
        } catch (Throwable $exception) {
            report($exception);
            $this->markApiUnavailable();

            return $results;
        }

        foreach ($responses as $index => $response) {
            if (! method_exists($response, 'successful') || ! $response->successful()) {
                continue;
            }

            $detail = $this->normalizePokemonDetail($response->json());

            if (! $detail || $detail['id'] > self::MAX_POKEMON_ID) {
                continue;
            }

            Cache::put($this->detailCacheKey($detail['id']), $detail, now()->addHours(self::CACHE_TTL_HOURS));
            Cache::put($this->detailCacheKey($detail['name']), $detail, now()->addHours(self::CACHE_TTL_HOURS));
            Cache::put($this->detailCacheKey($missing[$index]), $detail, now()->addHours(self::CACHE_TTL_HOURS));
            $results->put((int) $detail['id'], $detail);
        }

        if ($results->isNotEmpty()) {
            $this->markApiAvailable();
        }

        return $results;
    }

    private function normalizePokemonDetail(array $pokemon): ?array
    {
        $id = (int) ($pokemon['id'] ?? 0);

        if ($id < 1) {
            return null;
        }

        return [
            'id' => $id,
            'name' => $pokemon['name'] ?? ('pokemon-' . $id),
            'display_name' => $this->displayName($pokemon['name'] ?? ('pokemon-' . $id)),
            'types' => collect($pokemon['types'] ?? [])
                ->sortBy('slot')
                ->pluck('type.name')
                ->filter()
                ->values()
                ->all(),
            'image' => $pokemon['sprites']['other']['official-artwork']['front_default']
                ?? $pokemon['sprites']['front_default']
                ?? null,
            'sprite' => $pokemon['sprites']['front_default'] ?? null,
            'height' => (int) ($pokemon['height'] ?? 0),
            'weight' => (int) ($pokemon['weight'] ?? 0),
            'base_experience' => (int) ($pokemon['base_experience'] ?? 0),
            'abilities' => collect($pokemon['abilities'] ?? [])
                ->pluck('ability.name')
                ->filter()
                ->map(fn ($ability) => $this->displayName($ability))
                ->values()
                ->all(),
            'stats' => collect($pokemon['stats'] ?? [])
                ->map(fn ($stat) => [
                    'name' => $stat['stat']['name'] ?? '',
                    'label' => $this->statLabel($stat['stat']['name'] ?? ''),
                    'value' => (int) ($stat['base_stat'] ?? 0),
                ])
                ->filter(fn ($stat) => $stat['name'] !== '')
                ->values()
                ->all(),
        ];
    }

    private function applyFilters(Collection $baseList, array $filters, Collection $registered, bool $includeStatus, ?string &$warning): Collection
    {
        $filtered = $baseList;
        $query = Str::lower($filters['q']);

        if ($query !== '') {
            $filtered = $filtered->filter(function (array $pokemon) use ($query, $registered) {
                $local = $registered->get((int) $pokemon['id']);
                $haystack = collect([
                    $pokemon['name'] ?? '',
                    $pokemon['local_name'] ?? '',
                    $local?->nome,
                    $local?->apelido,
                ])
                    ->filter()
                    ->map(fn ($name) => Str::lower((string) $name))
                    ->implode(' ');

                return str_contains($haystack, $query);
            });
        }

        if ($filters['generation']) {
            $range = self::GENERATIONS[$filters['generation']];
            $filtered = $filtered->filter(fn (array $pokemon) => $pokemon['id'] >= $range['min'] && $pokemon['id'] <= $range['max']);
        }

        if ($filters['types'] !== []) {
            $idsByType = $this->pokemonIdsForTypes($filters['types']);

            if ($idsByType === null) {
                $warning = 'Nao foi possivel atualizar o filtro por tipo na PokeAPI. Mostrando correspondencias locais quando existirem.';
                $filtered = $filtered->filter(fn (array $pokemon) => $this->localPokemonHasAnyType($registered->get((int) $pokemon['id']), $filters['types']));
            } else {
                $filtered = $filtered->filter(fn (array $pokemon) => $idsByType->contains((int) $pokemon['id'])
                    || $this->localPokemonHasAnyType($registered->get((int) $pokemon['id']), $filters['types']));
            }
        }

        if ($includeStatus) {
            $filtered = $this->applyStatusFilter($filtered, $filters['status'], $registered);
        }

        return $filtered->values();
    }

    private function applyStatusFilter(Collection $items, string $status, Collection $registered): Collection
    {
        if ($status === 'registered') {
            return $items->filter(fn (array $pokemon) => $registered->has((int) $pokemon['id']))->values();
        }

        if ($status === 'unregistered') {
            return $items->filter(fn (array $pokemon) => ! $registered->has((int) $pokemon['id']))->values();
        }

        return $items->values();
    }

    private function sortPokemonList(Collection $items, string $sort): Collection
    {
        return match ($sort) {
            'name_asc' => $items->sortBy(fn (array $pokemon) => $pokemon['local_name'] ?: $pokemon['name'])->values(),
            'name_desc' => $items->sortByDesc(fn (array $pokemon) => $pokemon['local_name'] ?: $pokemon['name'])->values(),
            default => $items->sortBy('id')->values(),
        };
    }

    private function pokemonIdsForTypes(array $types): ?Collection
    {
        $ids = collect();

        foreach ($types as $type) {
            $typeIds = $this->fetchPokemonIdsByType($type);

            if ($typeIds === null) {
                return null;
            }

            $ids = $ids->merge($typeIds);
        }

        return $ids->unique()->values();
    }

    private function fetchPokemonIdsByType(string $type): ?Collection
    {
        if (! in_array($type, self::TYPES, true)) {
            return collect();
        }

        $cacheKey = "pokedex:type:v1:{$type}";

        try {
            $cached = Cache::get($cacheKey);

            if (is_array($cached)) {
                return collect($cached);
            }

            if ($this->isApiTemporarilyUnavailable()) {
                return null;
            }

            $response = Http::timeout(5)->get(self::API_BASE . "/type/{$type}");

            if (! $response->successful()) {
                $this->markApiUnavailable();

                return null;
            }

            $ids = collect($response->json('pokemon', []))
                ->map(fn (array $entry) => $this->extractPokemonId($entry['pokemon']['url'] ?? ''))
                ->filter(fn ($id) => $id && $id <= self::MAX_POKEMON_ID)
                ->unique()
                ->values()
                ->all();

            Cache::put($cacheKey, $ids, now()->addHours(self::CACHE_TTL_HOURS));
            $this->markApiAvailable();

            return collect($ids);
        } catch (Throwable $exception) {
            report($exception);
            $this->markApiUnavailable();

            return null;
        }
    }

    private function buildCardPokemon(?array $details, ?Pokemon $local, array $base): ?array
    {
        if (! $details && ! $local) {
            return null;
        }

        $id = (int) ($details['id'] ?? $local?->pokemon_id ?? $base['id']);
        $name = $details['display_name'] ?? $this->displayName($local?->nome ?? $base['name'] ?? ('pokemon-' . $id));
        $types = $details['types'] ?? $this->localTypes($local);

        return [
            'id' => $id,
            'number' => '#' . str_pad((string) $id, 3, '0', STR_PAD_LEFT),
            'name' => $name,
            'types' => $types,
            'image' => $this->localImage($local) ?? $details['image'] ?? $this->officialArtworkUrl($id),
            'sprite' => $details['sprite'] ?? null,
            'is_registered' => (bool) $local,
            'has_local_image' => (bool) $local?->imagem_local,
            'local_name' => $local?->nome,
            'nickname' => $local?->apelido,
            'detail_url' => route('pokedex.show', $id),
            'delete_url' => route('pokemon.excluir', $id, false),
        ];
    }

    private function buildDetailedPokemon(?array $details, ?Pokemon $local, int $id): array
    {
        $card = $this->buildCardPokemon($details, $local, ['id' => $id, 'name' => $local?->nome ?? ('pokemon-' . $id)]);

        return array_merge($card ?? [], [
            'height' => $details ? round($details['height'] / 10, 1) : ($local?->altura ? round($local->altura / 10, 1) : null),
            'weight' => $details ? round($details['weight'] / 10, 1) : ($local?->peso ? round($local->peso / 10, 1) : null),
            'base_experience' => $details['base_experience'] ?? null,
            'abilities' => $details['abilities'] ?? [],
            'stats' => $details['stats'] ?? $this->localStats($local),
            'flavor_text' => $local?->flavor_text,
        ]);
    }

    private function statusCounts(Collection $items, Collection $registered): array
    {
        $registeredCount = $items->filter(fn (array $pokemon) => $registered->has((int) $pokemon['id']))->count();

        return [
            'all' => $items->count(),
            'registered' => $registeredCount,
            'unregistered' => $items->count() - $registeredCount,
        ];
    }

    private function mergeLocalNames(Collection $apiList, Collection $registered): Collection
    {
        $merged = $apiList
            ->map(function (array $pokemon) use ($registered) {
                $local = $registered->get((int) $pokemon['id']);
                $pokemon['local_name'] = $local?->nome;

                return $pokemon;
            })
            ->keyBy('id');

        foreach ($registered as $local) {
            $id = (int) $local->pokemon_id;

            if (! $merged->has($id)) {
                $merged->put($id, [
                    'id' => $id,
                    'name' => Str::slug($local->nome ?: ('pokemon-' . $id)),
                    'local_name' => $local->nome,
                ]);
            }
        }

        return $merged->sortKeys()->values();
    }

    private function localBaseList(Collection $registered): Collection
    {
        return $registered
            ->map(fn (Pokemon $pokemon) => [
                'id' => (int) $pokemon->pokemon_id,
                'name' => Str::slug($pokemon->nome ?: ('pokemon-' . $pokemon->pokemon_id)),
                'local_name' => $pokemon->nome,
            ])
            ->values();
    }

    private function localPokemonHasAnyType(?Pokemon $pokemon, array $types): bool
    {
        if (! $pokemon) {
            return false;
        }

        return collect($this->localTypes($pokemon))
            ->map(fn ($type) => Str::lower((string) $type))
            ->intersect($types)
            ->isNotEmpty();
    }

    private function localTypes(?Pokemon $pokemon): array
    {
        if (! $pokemon) {
            return [];
        }

        return collect([$pokemon->tipo_primario, $pokemon->tipo_secundario])
            ->filter()
            ->map(fn ($type) => Str::lower((string) $type))
            ->values()
            ->all();
    }

    private function localStats(?Pokemon $pokemon): array
    {
        if (! $pokemon) {
            return [];
        }

        return [
            ['name' => 'hp', 'label' => 'HP', 'value' => (int) $pokemon->hp],
            ['name' => 'attack', 'label' => 'Ataque', 'value' => (int) $pokemon->ataque],
            ['name' => 'defense', 'label' => 'Defesa', 'value' => (int) $pokemon->defesa],
            ['name' => 'special-attack', 'label' => 'Sp. Atq', 'value' => (int) $pokemon->sp_ataque],
            ['name' => 'special-defense', 'label' => 'Sp. Def', 'value' => (int) $pokemon->sp_defesa],
            ['name' => 'speed', 'label' => 'Velocidade', 'value' => (int) $pokemon->velocidade],
        ];
    }

    private function localImage(?Pokemon $pokemon): ?string
    {
        if (! $pokemon) {
            return null;
        }

        if ($pokemon->imagem_local) {
            return route('pokemon.imagem', ['path' => ltrim($pokemon->imagem_local, '/')], false);
        }

        return $pokemon->imagem_url;
    }

    private function extractPokemonId(string $url): ?int
    {
        if (! preg_match('~/pokemon(?:-species)?/(\d+)/?$~', $url, $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    private function detailCacheKey(int|string $idOrName): string
    {
        return 'pokedex:pokemon-detail:v1:' . Str::lower((string) $idOrName);
    }

    private function displayName(string $name): string
    {
        return Str::of($name)->replace('-', ' ')->title()->toString();
    }

    private function statLabel(string $stat): string
    {
        return match ($stat) {
            'hp' => 'HP',
            'attack' => 'Ataque',
            'defense' => 'Defesa',
            'special-attack' => 'Sp. Atq',
            'special-defense' => 'Sp. Def',
            'speed' => 'Velocidade',
            default => $this->displayName($stat),
        };
    }

    private function officialArtworkUrl(int $id): string
    {
        return "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/{$id}.png";
    }

    private function isApiTemporarilyUnavailable(): bool
    {
        return (bool) Cache::get('pokedex:pokeapi-unavailable');
    }

    private function markApiUnavailable(): void
    {
        Cache::put('pokedex:pokeapi-unavailable', true, now()->addMinutes(5));
    }

    private function markApiAvailable(): void
    {
        Cache::forget('pokedex:pokeapi-unavailable');
    }
}
