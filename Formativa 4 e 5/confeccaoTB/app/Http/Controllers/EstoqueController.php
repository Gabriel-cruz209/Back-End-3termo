<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public function index(){
        $estoque = \App\Models\Estoque::all();
        return view('estoque.index', compact('estoque'));
    } 
}
