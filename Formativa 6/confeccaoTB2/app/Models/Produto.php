<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    protected $guarded = [];

    public function itensPedidos(): HasMany
    {
        return $this->hasMany(ItemPedido::class);
    }

    public function estoques(): HasMany
    {
        return $this->hasMany(Estoque::class);
    }

    public function reduzirEstoque(int $quantidade): bool
    {
        if ($quantidade <= 0) {
            throw new \InvalidArgumentException('Quantidade deve ser maior que zero.');
        }

        if ($this->estoque < $quantidade) {
            return false;
        }

        $this->estoque -= $quantidade;

        return $this->save();
    }

    public function aumentarEstoque(int $quantidade): bool
    {
        if ($quantidade <= 0) {
            throw new \InvalidArgumentException('Quantidade deve ser maior que zero.');
        }

        $this->estoque += $quantidade;

        return $this->save();
    }
}

