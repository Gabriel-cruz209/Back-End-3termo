<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produtos;

class ProdutosController extends Controller
{
    // Lista todos os produtos
    public function index()
    {
        $produtos = Produtos::all();
        return view('produtos.index', compact('produtos'));
    }

    // Exibe o formulário de cadastro
    public function create()
    {
        return view('produtos.create');
    }

    // Salva o novo produto no banco de dados
    public function store(Request $request)
    {
        $request->validate([
            'nome'          => 'required|string|max:255',
            'descricao'     => 'required|string',
            'preco_venda'   => 'required|numeric',
            'preco_custo'   => 'required|numeric',
            'codigo_barras' => 'nullable|string|unique:produtos',
        ]);

        Produtos::create($request->all());

        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    // Abre a tela de edição
    public function edit(Produtos $produto)
    {
        return view('produtos.edit', compact('produto'));
    }

    // Salva a alteração no banco
    public function update(Request $request, Produtos $produto)
    {
        $request->validate([
            'nome'          => 'required|string|max:255',
            'descricao'     => 'required|string',
            'preco_venda'   => 'required|numeric',
            'preco_custo'   => 'required|numeric',
            'codigo_barras' => 'nullable|string|unique:produtos,codigo_barras,' . $produto->id,
        ]);

        $produto->update($request->all());

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    // Exclui o produto
    public function destroy(Produtos $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Produto removido com sucesso!');
    }
}