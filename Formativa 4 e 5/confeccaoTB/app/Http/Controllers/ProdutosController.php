<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    public function index(){
        $produtos = \App\Models\Produtos::all();
        return view('produtos.index', compact('produtos'));
    }
}
