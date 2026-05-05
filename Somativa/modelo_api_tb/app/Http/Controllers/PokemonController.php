<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    private function fetchPokemonDetails($nameOrId)
    {
        $fetchKey = urlencode(strtolower($nameOrId));
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
        ];
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
