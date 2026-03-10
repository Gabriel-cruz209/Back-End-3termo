<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public function index(){
        $estoque = \App\Models\Estoque::all();
        return view('estoque.index', compact('estoque'));
    } 

    public function create() {
        return view('estoque.create');
    }

    public function store(Request $request){
        $request->validate([
            'nome_produto' => 'required|string',
            'nome_fornecedor' => 'required|string',
            'quantidade' => 'required|numeric',
            'data_validade' => 'required|date',
            'preco_custo' => 'required|numeric',
        ]);

         \App\Models\Estoque::create($request->all());

         return redirect()->route('estoque.index')->with('success', 'Produto cadastrado com sucesso!');
    }

}
