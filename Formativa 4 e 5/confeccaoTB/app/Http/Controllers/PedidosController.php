<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;

class PedidosController extends Controller
{
    // Lista todos os pedidos
    public function index()
    {
        $pedidos = Pedidos::all();
        return view('pedidos.index', compact('pedidos'));
    }

    // Exibe o formulário de cadastro
    public function create()
    {
        return view('pedidos.create');
    }

    // Salva o novo pedido no banco de dados
    public function store(Request $request)
    {
        $request->validate([
            'numero_pedido'    => 'required|numeric|unique:pedidos',
            'valor_total'      => 'required|numeric',
            'status'           => 'required|string',
            'metodo_pagamento' => 'required|string',
            'observacoes'      => 'nullable|string',
        ]);

        Pedidos::create($request->all());

        return redirect()->route('pedidos.index')->with('success', 'Pedido cadastrado com sucesso!');
    }

    // Abre a tela de edição
    public function edit(Pedidos $pedido)
    {
        return view('pedidos.edit', compact('pedido'));
        // return view('pedidos.edit', ['Pedidos' => $pedido]);
    }

    // Salva a alteração no banco
    public function update(Request $request, Pedidos $pedido)
    {
        $request->validate([
            'numero_pedido'    => 'required|numeric|unique:pedidos,numero_pedido,' . $pedido->id,
            'valor_total'      => 'required|numeric',
            'status'           => 'required|string',
            'metodo_pagamento' => 'required|string',
            'observacoes'      => 'nullable|string',
        ]);

        $pedido->update($request->all());

        return redirect()->route('pedidos.index')->with('success', 'Pedido atualizado!');
    }

    // Exclui o pedido
    public function destroy(Pedidos $pedido)
    {
        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', 'Pedido removido!');
    }
}