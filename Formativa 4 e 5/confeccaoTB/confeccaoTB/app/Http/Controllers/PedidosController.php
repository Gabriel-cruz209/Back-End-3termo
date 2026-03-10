<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PedidosController extends Controller
{
    public function index(){
        $pedidos = \App\Models\Pedidos::all();
        return view('pedidos.index', compact('pedidos'));
    }

    public function create() {
        return view('pedidos.create');
    }

    // Recebe os dados do formulario e salva no banco de dados
    public function store(Request $request) {
        // 1. vValidação simples para evitar dados vazios ou duplicados
        $request->validate([
            'numero_pedido'      => 'required|numeric',
            'valor_total'       => 'required|numeric',
            'status'     => 'required|string',
            'metodo_pagamento'  => 'required|string',
            'observacoes'  => 'nullable|string',
        ]);

        // 2. Salva o novo cliente
        \App\Models\Pedidos::create($request->all());

        // 3. redirect de volta para a lista com uma mensagem de sucesso
        return redirect()->route('pedidos.index')->with('success', 'Pedido cadastrado com sucesso!');
    }
}
