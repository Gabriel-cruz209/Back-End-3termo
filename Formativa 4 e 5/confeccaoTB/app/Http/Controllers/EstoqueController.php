<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estoque;
use Carbon\Carbon;

class EstoqueController extends Controller
{
    // Lista todos os itens do estoque
    public function index()
    {
        $estoque = Estoque::all();
        return view('estoque.index', compact('estoque'));
    }

    // Exibe o formulário de cadastro
    public function create()
    {
        return view('estoque.create');
    }

    // Salva o novo item no estoque
    public function store(Request $request)
    {
        // Normaliza o formato de data para o padrão aceito pelo MySQL (YYYY-MM-DD)
        if ($request->filled('data_validade') && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $request->input('data_validade'))) {
            $request->merge([
                'data_validade' => Carbon::createFromFormat('d/m/Y', $request->input('data_validade'))->format('Y-m-d'),
            ]);
        }

        $request->validate([
            'nome_produto'    => 'required|string',
            'nome_fornecedor' => 'required|string',
            'quantidade'      => 'required|numeric',
            'data_validade'   => 'required|date',
            'preco_custo'     => 'required|numeric',
        ]);

        Estoque::create($request->all());

        return redirect()->route('estoque.index')->with('success', 'Produto cadastrado no estoque com sucesso!');
    }

    // Abre a tela de edição
    public function edit(Estoque $estoque)
    {
        // Certifique-se de que a variável passada no compact é 'estoque'
        return view('estoque.edit', compact('estoque'));
    }

    // Salva a alteração no banco
    public function update(Request $request, Estoque $estoque)
    {
        // Normaliza o formato de data para o padrão aceito pelo MySQL (YYYY-MM-DD)
        if ($request->filled('data_validade') && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $request->input('data_validade'))) {
            $request->merge([
                'data_validade' => Carbon::createFromFormat('d/m/Y', $request->input('data_validade'))->format('Y-m-d'),
            ]);
        }

        $request->validate([
            'nome_produto'    => 'required|string',
            'nome_fornecedor' => 'required|string',
            'quantidade'      => 'required|numeric',
            'data_validade'   => 'required|date',
            'preco_custo'     => 'required|numeric',
        ]);

        $estoque->update($request->all());

        return redirect()->route('estoque.index')->with('success', 'Estoque atualizado com sucesso!');
    }

    // Exclui o item do estoque
    public function destroy(Estoque $estoque)
    {
        $estoque->delete();
        return redirect()->route('estoque.index')->with('success', 'Item removido do estoque!');
    }
}