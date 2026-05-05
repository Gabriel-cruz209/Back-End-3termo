<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class UsuarioController extends Controller
{
    public function index()
    {
        $id = rand(1, 100);
        $response = Http::get("https://dummyjson.com/user/{$id}");

        if ($response->successful()) {
            $usuario = $response->json();
            return view('usuario', compact('usuario'));
        }

        return "Erro ao buscar dados API!";
    }
}
