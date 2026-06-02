<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $guarded = [];

    protected $casts = [
        'valor_total' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'clientes_id');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ItemPedido::class);
    }

    public function calcularValorTotal(): float
    {
        return $this->itens->sum(fn (ItemPedido $item) => $item->subtotal);
    }

    public function atualizarValorTotal(): bool
    {
        $this->valor_total = $this->calcularValorTotal();

        return $this->save();
    }

    protected static function booted()
    {
        static::saved(function ($pedido) {
            // Se mudou para Finalizado
            if ($pedido->status === 'Finalizado' && $pedido->wasChanged('status')) {
                foreach ($pedido->itens as $item) {
                    // Como foi implementado no modelo Produto
                    $item->produto->reduzirEstoque($item->quantidade);

                    // Cria o log de movimentação do estoque de Saída
                    \App\Models\Estoque::create([
                        'produto_id' => $item->produto_id,
                        'tipo' => 'Saída',
                        'quantidade' => $item->quantidade,
                        'observacao' => "Venda via Pedido #{$pedido->id}",
                    ]);
                }
            }
        });
    }
}

