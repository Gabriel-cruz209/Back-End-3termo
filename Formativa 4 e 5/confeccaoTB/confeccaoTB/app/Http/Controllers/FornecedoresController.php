<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FornecedoresController extends Controller
{
    public function index(){
        $fornecedores = \App\Models\Fornecedores::all();
        return view('fornecedores.index', compact('fornecedores'));
    }

    public function create() {
        return view('fornecedores.create');
    }

    public function store(Request $request) {
        // 1. vValidação simples para evitar dados vazios ou duplicados
        $request->validate([
            'nome'      => 'required|string|max:255',
            'cnpj'       => 'required|string|unique:fornecedores',
            'email'     => 'required|email|unique:fornecedores',
            'telefone'  => 'required|string',
            'cep'  => 'nullable|string',
        ]);

        // 2. Salva o novo cliente
        \App\Models\Fornecedores::create($request->all());

        // 3. redirect de volta para a lista com uma mensagem de sucesso
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }
}
