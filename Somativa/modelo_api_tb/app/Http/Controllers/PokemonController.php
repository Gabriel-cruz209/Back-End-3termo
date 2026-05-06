<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PokemonController extends Controller
{
    public function index()
    {
        $pokemon = $this->fetchPokemonDetails(rand(1, 1025));

        if (! $pokemon) {
            return view('pokemon')->with('error', 'Erro ao buscar Pokémon inicial.');
        }

        return view('pokemon', compact('pokemon'));
    }

    public function search(Request $request)
    {
        $query = trim($request->query('query', ''));

        if ($query === '') {
            $pokemon = $this->fetchPokemonDetails(rand(1, 1025));
        } else {
            $pokemon = $this->fetchPokemonDetails($query);
        }

        if (! $pokemon) {
            return response()->json(['error' => 'Pokémon não encontrado.'], 404);
        }

        return response()->json(['pokemon' => $pokemon]);
    }

    public function store(Request $request)
    {
        $manualFields = [
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
            'flavor_text',
            'apelido',
        ];

        foreach ($manualFields as $field) {
            if ($request->has($field)) {
                return $this->storeManual($request);
            }
        }

        if ($request->hasFile('imagem')) {
            return $this->storeManual($request);
        }

        return $this->storeFromApi($request);
    }

    public function storeSimple(Request $request)
    {
        $dados = $request->validate([
            'pokemon_id' => ['nullable', 'integer', 'min:1'],
            'nome' => ['required', 'string', 'min:2', 'max:255'],
            'tipo' => ['required_without:tipo_primario', 'string', 'max:80'],
            'tipo_primario' => ['nullable', 'string', 'max:80'],
            'tipo_secundario' => ['nullable', 'string', 'max:80'],
            'hp' => ['nullable', 'integer', 'min:0', 'max:999'],
            'ataque' => ['required', 'integer', 'min:0', 'max:999'],
            'defesa' => ['nullable', 'integer', 'min:0', 'max:999'],
            'sp_ataque' => ['nullable', 'integer', 'min:0', 'max:999'],
            'sp_defesa' => ['nullable', 'integer', 'min:0', 'max:999'],
            'velocidade' => ['nullable', 'integer', 'min:0', 'max:999'],
            'altura' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'peso' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'flavor_text' => ['nullable', 'string', 'max:2000'],
            'apelido' => ['nullable', 'string', 'max:255'],
            'imagem' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'nome.required' => 'Informe o nome do Pokemon.',
            'tipo.required_without' => 'Informe o tipo do Pokemon.',
            'ataque.required' => 'Informe o ataque do Pokemon.',
            'imagem.image' => 'O arquivo enviado precisa ser uma imagem.',
            'imagem.mimes' => 'A imagem deve estar em JPG, PNG ou WEBP.',
            'imagem.max' => 'A imagem deve ter no maximo 4MB.',
        ]);

        $pokemonId = isset($dados['pokemon_id'])
            ? (int) $dados['pokemon_id']
            : $this->nextCustomPokemonId();

        if (Pokemon::jaExiste($pokemonId)) {
            return response()->json([
                'success' => false,
                'message' => 'Este Pokemon ja esta na sua Pokedex!',
            ], 409);
        }

        $tipoPrimario = $dados['tipo_primario'] ?? $dados['tipo'];
        $imagemLocal = $this->storeUploadedImage($request, $pokemonId, $dados['nome']);

        try {
            $pokemonSalvo = Pokemon::create([
                'nome' => $dados['nome'],
                'pokemon_id' => $pokemonId,
                'tipo_primario' => $tipoPrimario,
                'tipo_secundario' => $dados['tipo_secundario'] ?? null,
                'hp' => (int) ($dados['hp'] ?? 0),
                'ataque' => (int) $dados['ataque'],
                'defesa' => (int) ($dados['defesa'] ?? 0),
                'sp_ataque' => (int) ($dados['sp_ataque'] ?? 0),
                'sp_defesa' => (int) ($dados['sp_defesa'] ?? 0),
                'velocidade' => (int) ($dados['velocidade'] ?? 0),
                'altura' => (int) ($dados['altura'] ?? 0),
                'peso' => (int) ($dados['peso'] ?? 0),
                'imagem_url' => null,
                'imagem_local' => $imagemLocal,
                'flavor_text' => $dados['flavor_text'] ?? null,
                'apelido' => $dados['apelido'] ?? null,
            ]);
        } catch (QueryException $exception) {
            report($exception);

            if ((string) $exception->getCode() === '23000') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este Pokemon ja esta na sua Pokedex!',
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar. Verifique os dados e tente novamente.',
            ], 500);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar. Verifique os dados e tente novamente.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pokemon cadastrado com sucesso!',
            'mensagem' => 'Pokemon cadastrado com sucesso!',
            'id_gerado' => $pokemonSalvo->pokemon_id,
            'pokemon' => $pokemonSalvo,
            'dados_salvos' => $pokemonSalvo,
        ], 201);
    }

    public function image(string $path)
    {
        $path = $this->normalizeLocalImagePath($path);

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $mimeType = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';

        return response(Storage::disk('public')->get($path), 200)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=31536000');
    }

    public function destroy(int $id)
    {
        $pokemon = Pokemon::where('pokemon_id', $id)->first();

        if (! $pokemon) {
            return response()->json([
                'success' => false,
                'message' => 'Pokemon nao encontrado na sua Pokedex.',
            ], 404);
        }

        $imagemLocal = $this->normalizeLocalImagePath($pokemon->imagem_local);

        try {
            $pokemon->delete();

            if ($imagemLocal && Storage::disk('public')->exists($imagemLocal)) {
                Storage::disk('public')->delete($imagemLocal);
            }
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir. Tente novamente.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pokemon excluido com sucesso!',
        ]);
    }

    private function storeManual(Request $request)
    {
        $dados = $request->validate([
            'pokemon_id' => ['required', 'integer', 'min:1'],
            'nome' => ['required', 'string', 'min:2', 'max:255'],
            'tipo_primario' => ['required', 'string', 'max:80'],
            'tipo_secundario' => ['nullable', 'string', 'max:80'],
            'hp' => ['required', 'integer', 'min:0', 'max:999'],
            'ataque' => ['required', 'integer', 'min:0', 'max:999'],
            'defesa' => ['required', 'integer', 'min:0', 'max:999'],
            'sp_ataque' => ['required', 'integer', 'min:0', 'max:999'],
            'sp_defesa' => ['required', 'integer', 'min:0', 'max:999'],
            'velocidade' => ['required', 'integer', 'min:0', 'max:999'],
            'altura' => ['required', 'integer', 'min:0', 'max:9999'],
            'peso' => ['required', 'integer', 'min:0', 'max:9999'],
            'flavor_text' => ['nullable', 'string', 'max:2000'],
            'apelido' => ['nullable', 'string', 'max:255'],
            'imagem' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'pokemon_id.required' => 'Informe o ID do Pokémon.',
            'nome.required' => 'Informe o nome do Pokémon.',
            'tipo_primario.required' => 'Informe o tipo primário.',
            'imagem.image' => 'O arquivo enviado precisa ser uma imagem.',
            'imagem.mimes' => 'A imagem deve estar em JPG, PNG ou WEBP.',
            'imagem.max' => 'A imagem deve ter no máximo 4MB.',
        ]);

        $pokemonId = (int) $dados['pokemon_id'];

        if (Pokemon::jaExiste($pokemonId)) {
            return response()->json([
                'success' => false,
                'message' => 'Este Pokémon já está na sua Pokédex!',
            ], 409);
        }

        $imagemLocal = $this->storeUploadedImage($request, $pokemonId, $dados['nome']);

        try {
            $pokemonSalvo = Pokemon::create([
                'nome' => $dados['nome'],
                'pokemon_id' => $pokemonId,
                'tipo_primario' => $dados['tipo_primario'],
                'tipo_secundario' => $dados['tipo_secundario'] ?? null,
                'hp' => (int) $dados['hp'],
                'ataque' => (int) $dados['ataque'],
                'defesa' => (int) $dados['defesa'],
                'sp_ataque' => (int) $dados['sp_ataque'],
                'sp_defesa' => (int) $dados['sp_defesa'],
                'velocidade' => (int) $dados['velocidade'],
                'altura' => (int) $dados['altura'],
                'peso' => (int) $dados['peso'],
                'imagem_url' => null,
                'imagem_local' => $imagemLocal,
                'flavor_text' => $dados['flavor_text'] ?? null,
                'apelido' => $dados['apelido'] ?? null,
            ]);
        } catch (QueryException $exception) {
            report($exception);

            if ((string) $exception->getCode() === '23000') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este Pokémon já está na sua Pokédex!',
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar. Verifique os dados e tente novamente.',
            ], 500);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar. Verifique os dados e tente novamente.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pokémon cadastrado com sucesso!',
            'pokemon' => $pokemonSalvo,
        ], 201);
    }

    private function storeFromApi(Request $request)
    {
        $dados = $request->validate([
            'pokemon_id' => ['required', 'integer', 'min:1', 'max:1025'],
        ], [
            'pokemon_id.required' => 'Informe o ID do Pokémon.',
            'pokemon_id.integer' => 'O ID do Pokémon deve ser numérico.',
            'pokemon_id.min' => 'O ID do Pokémon deve ser maior que zero.',
            'pokemon_id.max' => 'O ID do Pokémon deve existir na PokéAPI.',
        ]);

        $pokemonId = (int) $dados['pokemon_id'];

        if (Pokemon::jaExiste($pokemonId)) {
            return response()->json([
                'success' => false,
                'message' => 'Este Pokémon já está na sua Pokédex!',
            ], 409);
        }

        // Busca os dados completos na PokeAPI antes de montar o registro local.
        $pokemonResponse = Http::timeout(12)->get("https://pokeapi.co/api/v2/pokemon/{$pokemonId}");
        $speciesResponse = Http::timeout(12)->get("https://pokeapi.co/api/v2/pokemon-species/{$pokemonId}");

        if (! $pokemonResponse->successful() || ! $speciesResponse->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível buscar os dados. Tente novamente.',
            ], 502);
        }

        $pokemon = $pokemonResponse->json();
        $species = $speciesResponse->json();
        $stats = $this->mapBaseStats($pokemon['stats'] ?? []);
        $imagemUrl = $pokemon['sprites']['other']['official-artwork']['front_default']
            ?? $pokemon['sprites']['front_default']
            ?? null;

        // O download local é preferencial; se falhar, o cadastro segue usando a URL remota.
        $imagemLocal = $this->downloadPokemonImage($imagemUrl, (int) $pokemon['id'], $pokemon['name']);

        $payload = [
            'nome' => $pokemon['name'],
            'pokemon_id' => (int) $pokemon['id'],
            'tipo_primario' => $pokemon['types'][0]['type']['name'] ?? 'normal',
            'tipo_secundario' => $pokemon['types'][1]['type']['name'] ?? null,
            'hp' => $stats['hp'] ?? 0,
            'ataque' => $stats['attack'] ?? 0,
            'defesa' => $stats['defense'] ?? 0,
            'sp_ataque' => $stats['special-attack'] ?? 0,
            'sp_defesa' => $stats['special-defense'] ?? 0,
            'velocidade' => $stats['speed'] ?? 0,
            'altura' => (int) ($pokemon['height'] ?? 0),
            'peso' => (int) ($pokemon['weight'] ?? 0),
            'imagem_url' => $imagemUrl,
            'imagem_local' => $imagemLocal,
            'flavor_text' => $this->translateFlavorText($species),
            'apelido' => null,
        ];

        try {
            $pokemonSalvo = Pokemon::create($payload);
        } catch (QueryException $exception) {
            report($exception);

            if ((string) $exception->getCode() === '23000') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este Pokémon já está na sua Pokédex!',
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar. Verifique os dados e tente novamente.',
            ], 500);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar. Verifique os dados e tente novamente.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pokémon cadastrado com sucesso!',
            'pokemon' => $pokemonSalvo,
        ], 201);
    }

    private function fetchPokemonDetails($nameOrId)
    {
        $fetchKey = urlencode(strtolower((string) $nameOrId));
        $pokemonResponse = Http::get("https://pokeapi.co/api/v2/pokemon/{$fetchKey}");

        if (! $pokemonResponse->successful()) {
            return null;
        }

        $pokemon = $pokemonResponse->json();
        $speciesResponse = Http::get($pokemon['species']['url']);

        if (! $speciesResponse->successful()) {
            return null;
        }

        $species = $speciesResponse->json();
        $evolutionChain = [];

        if (! empty($species['evolution_chain']['url'])) {
            $evolutionResponse = Http::get($species['evolution_chain']['url']);

            if ($evolutionResponse->successful()) {
                $chainNames = $this->gatherEvolutionNames($evolutionResponse->json()['chain']);
                $evolutionChain = $this->fetchEvolutionSprites($chainNames);
            }
        }

        return [
            'id' => $pokemon['id'],
            'name' => ucfirst($pokemon['name']),
            'types' => $pokemon['types'],
            'primary_type' => $pokemon['types'][0]['type']['name'] ?? 'normal',
            'height' => $pokemon['height'],
            'weight' => $pokemon['weight'],
            'base_experience' => $pokemon['base_experience'],
            'stats' => $pokemon['stats'],
            'sprites' => $pokemon['sprites'],
            'evolution_chain' => $evolutionChain,
            'variant_forms' => $this->buildVariantForms($species, $pokemon['name']),
            'ja_cadastrado' => $this->isPokemonRegistered((int) $pokemon['id']),
        ];
    }

    private function mapBaseStats(array $stats): array
    {
        $mappedStats = [];

        foreach ($stats as $stat) {
            $name = $stat['stat']['name'] ?? null;

            if ($name) {
                $mappedStats[$name] = (int) ($stat['base_stat'] ?? 0);
            }
        }

        return $mappedStats;
    }

    /**
     * Extrai o flavor text da species e traduz para pt-BR via MyMemory.
     * Prefere o texto em espanhol como fonte (traducao mais natural para o portugues).
     */
    private function translateFlavorText(array $species): ?string
    {
        $entries      = $species['flavor_text_entries'] ?? [];
        $sourceLang   = 'en';
        $rawText      = null;

        // Tenta espanhol primeiro (idioma mais proximo do portugues na PokeAPI).
        foreach ($entries as $entry) {
            if (($entry['language']['name'] ?? null) === 'es') {
                $rawText    = $this->normalizeFlavorText($entry['flavor_text'] ?? null);
                $sourceLang = 'es';
                break;
            }
        }

        // Fallback: ingles.
        if (! $rawText) {
            foreach ($entries as $entry) {
                if (($entry['language']['name'] ?? null) === 'en') {
                    $rawText = $this->normalizeFlavorText($entry['flavor_text'] ?? null);
                    break;
                }
            }
        }

        if (! $rawText) {
            return null;
        }

        return $this->translateToPortuguese($rawText, $sourceLang);
    }

    private function extractFlavorText(array $species): ?string
    {
        $entries = $species['flavor_text_entries'] ?? [];

        // A PokeAPI nao possui entradas em pt-BR ou pt.
        // Preferimos espanhol (mais proximo do portugues) e, como fallback, ingles.
        $preferredLanguages = ['es', 'en'];

        foreach ($preferredLanguages as $language) {
            foreach ($entries as $entry) {
                if (($entry['language']['name'] ?? null) === $language) {
                    return $this->normalizeFlavorText($entry['flavor_text'] ?? null);
                }
            }
        }

        foreach ($entries as $entry) {
            $text = $this->normalizeFlavorText($entry['flavor_text'] ?? null);

            if ($text) {
                return $text;
            }
        }

        return null;
    }

    private function translateToPortuguese(?string $text, string $sourceLang = 'en'): ?string
    {
        if (! $text) {
            return null;
        }

        try {
            $response = Http::timeout(8)->get('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl'     => $sourceLang,
                'tl'     => 'pt',
                'dt'     => 't',
                'q'      => $text,
            ]);

            if (! $response->successful()) {
                return $text;
            }

            $data = $response->json();
            
            $translated = '';
            if (isset($data[0]) && is_array($data[0])) {
                foreach ($data[0] as $segment) {
                    if (isset($segment[0])) {
                        $translated .= $segment[0];
                    }
                }
            }

            $translated = trim($translated);

            return $translated !== '' ? $translated : $text;
        } catch (Throwable) {
            return $text;
        }
    }

    private function normalizeFlavorText(?string $text): ?string
    {
        if (! $text) {
            return null;
        }

        return trim((string) preg_replace('/\s+/', ' ', str_replace(["\n", "\r", "\f"], ' ', $text)));
    }

    private function storeUploadedImage(Request $request, int $pokemonId, string $pokemonName): ?string
    {
        if (! $request->hasFile('imagem')) {
            return null;
        }

        $image = $request->file('imagem');
        $fileName = sprintf(
            'pokemon_%d_%s.%s',
            $pokemonId,
            Str::slug($pokemonName),
            $image->extension()
        );

        return $image->storeAs('pokemons', $fileName, 'public');
    }

    private function normalizeLocalImagePath(?string $path): ?string
    {
        $path = ltrim(str_replace('\\', '/', (string) $path), '/');

        if ($path === '' || str_contains($path, '..') || ! str_starts_with($path, 'pokemons/')) {
            return null;
        }

        return $path;
    }

    private function nextCustomPokemonId(): int
    {
        return max(10000, ((int) Pokemon::max('pokemon_id')) + 1);
    }

    private function downloadPokemonImage(?string $imageUrl, int $pokemonId, string $pokemonName): ?string
    {
        if (! $imageUrl) {
            return null;
        }

        try {
            $response = Http::timeout(15)->get($imageUrl);

            if (! $response->successful() || $response->body() === '') {
                return null;
            }

            $fileName = sprintf('pokemon_%d_%s.png', $pokemonId, Str::slug($pokemonName));
            $path = "pokemons/{$fileName}";

            Storage::disk('public')->put($path, $response->body());

            return $path;
        } catch (Throwable) {
            return null;
        }
    }

    private function isPokemonRegistered(int $pokemonId): bool
    {
        try {
            return Pokemon::jaExiste($pokemonId);
        } catch (Throwable) {
            return false;
        }
    }

    private function gatherEvolutionNames(array $chain, array &$names = [])
    {
        if (! in_array($chain['species']['name'], $names, true)) {
            $names[] = $chain['species']['name'];
        }

        foreach ($chain['evolves_to'] as $next) {
            $this->gatherEvolutionNames($next, $names);
        }

        return $names;
    }

    private function fetchEvolutionSprites(array $names)
    {
        $evolutions = [];

        foreach ($names as $name) {
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$name}");

            if (! $response->successful()) {
                continue;
            }

            $data = $response->json();
            $evolutions[] = [
                'name' => ucfirst($name),
                'sprite' => $data['sprites']['other']['official-artwork']['front_default'] ?? $data['sprites']['front_default'] ?? null,
                'types' => $data['types'] ?? [],
            ];
        }

        return $evolutions;
    }

    private function buildVariantForms(array $species, string $mainPokemonName)
    {
        $variants = [];

        foreach ($species['varieties'] as $variety) {
            $name = $variety['pokemon']['name'];

            if ($name === $mainPokemonName) {
                continue;
            }

            $response = Http::get($variety['pokemon']['url']);

            if (! $response->successful()) {
                continue;
            }

            $variantData = $response->json();

            $variants[] = [
                'name' => ucfirst(str_replace('-', ' ', $name)),
                'query_name' => $name,
                'type' => $variantData['types'][0]['type']['name'] ?? 'normal',
                'sprite' => $variantData['sprites']['other']['official-artwork']['front_default'] ?? $variantData['sprites']['front_default'] ?? null,
                'label' => $this->detectVariantLabel($name),
            ];
        }

        return $variants;
    }

    private function detectVariantLabel(string $name)
    {
        $lower = strtolower($name);

        if (str_contains($lower, 'mega')) {
            return 'Mega';
        }

        if (str_contains($lower, 'alola')) {
            return 'Alola';
        }

        if (str_contains($lower, 'galar')) {
            return 'Galar';
        }

        if (str_contains($lower, 'hisuian') || str_contains($lower, 'hisui')) {
            return 'Hisui';
        }

        if (str_contains($lower, 'paldean') || str_contains($lower, 'paldea')) {
            return 'Paldea';
        }

        if (str_contains($lower, 'regional')) {
            return 'Regional';
        }

        return 'Variante';
    }
}
