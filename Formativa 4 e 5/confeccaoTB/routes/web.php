<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\FornecedoresController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clientes', [ClienteController::class, 'index'])-> name('clientes.index');

Route::get('/pedidos', [PedidosController::class, 'index'])-> name('pedidos.index');

Route::get('/estoques', [EstoqueController::class, 'index'])-> name('estoque.index');

Route::get('/produtos', [ProdutosController::class, 'index'])->name('produtos.index');

Route::get('/fornecedores', [FornecedoresController::class, 'index'])-> name('fornecedores.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
