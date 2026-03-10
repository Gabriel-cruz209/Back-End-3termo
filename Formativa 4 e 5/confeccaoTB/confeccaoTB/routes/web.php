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
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');



Route::get('/pedidos', [PedidosController::class, 'index'])-> name('pedidos.index');
Route::get('/pedidos/create', [PedidosController::class, 'create'])->name('pedidos.create');
Route::post('/pedidos', [PedidosController::class, 'store'])->name('pedidos.store');

Route::get('/estoques', [EstoqueController::class, 'index'])-> name('estoque.index');
Route::get('/estoques/create', [EstoqueController::class, 'create'])->name('estoque.create');
Route::post('/estoques', [EstoqueController::class, 'store'])->name('estoque.store');

Route::get('/produtos', [ProdutosController::class, 'index'])->name('produtos.index');
Route::get('/produtos/create', [ProdutosController::class, 'create'])->name('produtos.create');
Route::post('/produtos', [ProdutosController::class, 'store'])->name('produtos.store');

Route::get('/fornecedores', [FornecedoresController::class, 'index'])-> name('fornecedores.index');
Route::get('/fornecedores/create', [FornecedoresController::class, 'create'])->name('fornecedores.create');
Route::post('/fornecedores', [FornecedoresController::class, 'store'])->name('fornecedores.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
