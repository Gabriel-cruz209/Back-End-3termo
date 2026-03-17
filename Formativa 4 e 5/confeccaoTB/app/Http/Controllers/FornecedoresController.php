<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedores;

class FornecedoresController extends Controller
{
    // 1. Lista todos os fornecedores
    public function index()
    {
        $fornecedores = Fornecedores::all();
        return view('fornecedores.index', compact('fornecedores'));
    }

    // 2. Exibe o formulário de cadastro
    public function create()
    {
        return view('fornecedores.create');
    }

    // 3. Salva o novo fornecedor no banco de dados
    public function store(Request $request)
    {
        $request->validate([
            'nome'      => 'required|string|max:255',
            'cnpj'      => 'required|string|unique:fornecedores',
            'email'     => 'required|email|unique:fornecedores',
            'telefone'  => 'required|string',
            'endereco'  => 'nullable|string',
        ]);

        Fornecedores::create($request->all());

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    // 4. Abre a tela de edição
    public function edit(Fornecedores $fornecedor)
    {
        return view('fornecedores.edit', compact('fornecedor'));
    }

    // 5. Salva a alteração no banco
    public function update(Request $request, Fornecedores $fornecedor)
    {
        $request->validate([
            'nome'     => 'required|string|max:255',
            'cnpj'     => 'required|string|unique:fornecedores,cnpj,' . $fornecedor->id,
            'email'    => 'required|email|unique:fornecedores,email,' . $fornecedor->id,
            'telefone' => 'required|string',
            'endereco' => 'nullable|string',
        ]);

        $fornecedor->update($request->all());

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    // 6. Exclui o fornecedor
    public function destroy(Fornecedores $fornecedor)
    {
        $fornecedor->delete();
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor removido com sucesso!');
    }
}