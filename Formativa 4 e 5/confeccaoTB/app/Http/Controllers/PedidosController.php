<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

class PedidosController extends Controller
{
    public function index(){
        $pedidos = \App\Models\Pedidos::all();
        return view('pedidos.index', compact('pedidos'));
    }
}
