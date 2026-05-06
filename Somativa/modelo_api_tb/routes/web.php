<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\PokedexController;
use App\Http\Controllers\BatalhaController;
use App\Http\Controllers\UsuarioController;
use App\Models\Usuario;

Route::get('/', [PokedexController::class, 'index'])->name('home');
Route::get('usuarios', [UsuarioController::class, 'index']);
Route::get('usuario/{id}', function ($id){
    $response = Http::get("https://dummyjson.com/user/{$id}");

    if($response->successful()) {
        $dados = $response->json();
        return response()->json([
            'status' => 'Conectado com sucesso!',
            'resultado' => [
                'identificador' => $dados['id'],
                'nome_usuario' => ucfirst($dados['firstName']),
                'idade' => ucfirst($dados['age'])
            ]
        ], 200);
    }
    return response()->json(['erro' => 'Usuario não encontrado'], 404);
});

Route::post('usuario/novo', function (Request $request) {
    $dados = $request->validate([
        'nome' => 'required|string|min:3',
        'idade' => 'required|integer',
        'cor_olhos' => 'required|string|min:3'
    ]);

    $usuario = Usuario::create([
        'nome' => $dados['nome'],
        'idade' => $dados['idade'],
        'cor_olhos' => $dados['cor_olhos']
    ]);

    return response()->json([
        'mensagem' => 'Usuário cadastrado com sucesso!',
        'id_gerado' => $usuario->id,
        'dados_salvos' => $usuario
    ], 201);
});

Route::get('api/pokemon', [PokemonController::class, 'search']);
Route::get('pokedex', [PokedexController::class, 'index'])->name('pokedex.index');
Route::get('pokedex/buscar', [PokedexController::class, 'buscar'])->name('pokedex.buscar');
Route::get('pokedex/{id}', [PokedexController::class, 'show'])->whereNumber('id')->name('pokedex.show');
Route::post('pokemon/cadastrar', [PokemonController::class, 'store'])->name('pokemon.cadastrar');
Route::post('pokemon/novo', [PokemonController::class, 'storeSimple'])->name('pokemon.novo');
Route::delete('pokemon/{id}/excluir', [PokemonController::class, 'destroy'])->whereNumber('id')->name('pokemon.excluir');
Route::delete('pokemon/{id}', [PokemonController::class, 'destroy'])->whereNumber('id');
Route::get('pokemon/imagem/{path}', [PokemonController::class, 'image'])->where('path', '.*')->name('pokemon.imagem');

Route::get('batalha', [BatalhaController::class, 'index'])->name('batalha.index');
Route::post('batalha/iniciar', [BatalhaController::class, 'iniciar'])->name('batalha.iniciar');
Route::post('batalha/turno', [BatalhaController::class, 'turno'])->name('batalha.turno');
Route::post('batalha/proximo', [BatalhaController::class, 'proximo'])->name('batalha.proximo');
Route::get('batalha/resetar', [BatalhaController::class, 'resetar'])->name('batalha.resetar');

Route::get('pokemon/{nome}', function ($nome) {
    $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$nome}");

    if ($response->successful()) {
        $dados = $response->json();
        return response()->json([
            'status' => 'Conectado com sucesso!',
            'resultado' => [
                'identificador' => $dados['id'],
                'nome_do_pokemon' => ucfirst($dados['name']),
                'foto' => $dados['sprites']['front_default']
            ]
        ], 200);
    }
    return response()->json(['erro' => 'Pokemon não encontrado'], 404);
});

Route::get('/fix-translations', function () {
    $pokemons = \App\Models\Pokemon::whereNotNull('flavor_text')->get();
    $count = 0;
    foreach ($pokemons as $pokemon) {
        $text = $pokemon->flavor_text;
        if (str_contains(strtolower($text), ' is ') || str_contains(strtolower($text), ' the ') || str_contains(strtolower($text), ' it ') || str_contains(strtolower($text), ' of ') || str_contains(strtolower($text), ' by ') || str_contains(strtolower($text), ' a ')) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(8)->get('https://translate.googleapis.com/translate_a/single', [
                    'client' => 'gtx',
                    'sl'     => 'en',
                    'tl'     => 'pt',
                    'dt'     => 't',
                    'q'      => $text,
                ]);
                if ($response->successful()) {
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
                    if ($translated !== '' && $translated !== $text) {
                        $pokemon->flavor_text = $translated;
                        $pokemon->save();
                        $count++;
                    }
                }
            } catch (\Throwable $e) {}
        }
    }
    return "Translated $count pokemons.";
});
