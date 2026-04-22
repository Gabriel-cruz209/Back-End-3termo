<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


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

Route::post('usuario/novo', function (Request $request){
    $dados = $request->validate([
        'nome' => 'required|string|min:3',
        'idade' => 'required|integer',
        'cor_olhos' => 'required|string|min:3'
    ]);
    return response()->json([
        'mensagem' => 'Usuario cadastrado com sucesso!',
        'id_gerado' => rand(1000, 9999),
        'dados_recebidos' => $dados
    ], 201);
});