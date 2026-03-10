<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(){
        $clientes = \App\Models\Clientes::all();
        return view('clientes.index', compact('clientes'));
    }

    public function create() {
        return view('clientes.create');
    }

    // Recebe os dados do formulario e salva no banco de dados
    public function store(Request $request) {
        // 1. vValidação simples para evitar dados vazios ou duplicados
        $request->validate([
            'nome'      => 'required|string|max:255',
            'cpf'       => 'required|string|unique:clientes',
            'email'     => 'required|email|unique:clientes',
            'telefone'  => 'required|string',
            'endereco'  => 'nullable|string',
        ]);

        // 2. Salva o novo cliente
        \App\Models\Clientes::create($request->all());

        // 3. redirect de volta para a lista com uma mensagem de sucesso
        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }
}
