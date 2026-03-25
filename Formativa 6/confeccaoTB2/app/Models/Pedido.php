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
}

