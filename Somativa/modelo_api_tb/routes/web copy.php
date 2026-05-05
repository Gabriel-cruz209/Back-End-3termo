<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

// Route::get('/', function () {
//     return view('welcome');
// });

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
    

