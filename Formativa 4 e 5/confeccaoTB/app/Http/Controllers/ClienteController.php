<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clientes; 

class ClienteController extends Controller
{
    // Lista todos os clientes
    public function index() 
    {
        $clientes = Clientes::all(); 
        return view('Clientes.index', compact('clientes'));
    }

    // Exibe o formulário de cadastro (a janela/página de inserção)
    public function create() 
    {
        return view('clientes.create');
    }

    // Recebe os dados do formulário e salva no banco de dados
    public function store(Request $request) 
    {
        // 1. Validação simples para evitar dados vazios ou duplicados
        $request->validate([
            'nome'     => 'required|string|max:255',
            'cpf'      => 'required|string|unique:clientes',
            'email'    => 'required|email|unique:clientes',
            'telefone' => 'required|string',
            'endereco' => 'nullable|string',
        ]);

        // 2. Salva o novo cliente
        Clientes::create($request->all());

        // 3. Redireciona de volta para a lista com uma mensagem de sucesso
        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }
        // Abre a tela de edição
        public function edit(Clientes $cliente) 
    {
        return view('clientes.edit', compact('cliente'));
    }

// Salva a alteração no banco
public function update(Request $request, Clientes $cliente) 
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'cpf' => 'required|string|unique:clientes,cpf,' . $cliente->id,
        'email' => 'required|email|unique:clientes,email,' . $cliente->id,
        'telefone' => 'required',
    ]);

    $cliente->update($request->all());
    return redirect()->route('clientes.index')->with('success', 'Cliente atualizado!');
}

// Exclui o cliente
public function destroy(Clientes $cliente) 
{
    $cliente->delete();
    return redirect()->route('clientes.index')->with('success', 'Cliente removido!');
}

}