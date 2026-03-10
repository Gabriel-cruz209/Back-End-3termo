<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    public function index(){
        $produtos = \App\Models\Produtos::all();
        return view('produtos.index', compact('produtos'));
    }

    public function create() {
        return view('produtos.create');
    }

    // Recebe os dados do formulario e salva no banco de dados
    public function store(Request $request) {
        // 1. vValidação simples para evitar dados vazios ou duplicados
        $request->validate([
            'nome'      => 'required|string|max:255',
            'descricao'       => 'required|string|',
            'preco_venda'     => 'required|number',
            'preco_custo'  => 'required|number',
            'codigo_barras'  => 'nullable|string',
        ]);

        // 2. Salva o novo cliente
        \App\Models\Produtos::create($request->all());

        // 3. redirect de volta para a lista com uma mensagem de sucesso
        return redirect()->route('produtos.index')->with('success', 'Produtos cadastrado com sucesso!');
    }
}
