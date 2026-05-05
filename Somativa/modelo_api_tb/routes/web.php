<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\UsuarioController;
use App\Models\Usuario;

Route::view('/', 'pokemon');
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
Route::get('pokedex', [PokemonController::class, 'index']);
Route::post('pokemon/cadastrar', [PokemonController::class, 'store']);

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

Route::post('pokemon/novo', function (Request $request){
        $dados = $request->validate([
            'nome' => 'required|string|min:3',
            'tipo' => 'required|string',
            'ataque' => 'required|integer',
        ]);
        return response()->json([
            'mensagem' => 'Pokemon cadastrado com sucesso!',
            'id_gerado' => rand(1000, 9999),
            'dados_recebidos' => $dados
        ], 201);
    });
