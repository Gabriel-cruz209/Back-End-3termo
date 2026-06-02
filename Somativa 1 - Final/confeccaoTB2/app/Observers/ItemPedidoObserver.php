<?php

namespace App\Observers;

use App\Models\ItemPedido;

class ItemPedidoObserver
{
    public function creating(ItemPedido $itemPedido)
    {
        $produto = $itemPedido->produto;

        if (! $produto) {
            throw new \RuntimeException('Produto inválido para item de pedido.');
        }

        if (! $produto->reduzirEstoque($itemPedido->quantidade)) {
            throw new \RuntimeException('Estoque insuficiente para o produto ' . $produto->id);
        }

        $itemPedido->preco_unitario = $itemPedido->preco_unitario ?? $produto->preco_venda;
    }

    public function updating(ItemPedido $itemPedido)
    {
        $original = $itemPedido->getOriginal();

        $oldQuantidade = (int) ($original['quantidade'] ?? 0);
        $newQuantidade = (int) $itemPedido->quantidade;
        $diff = $newQuantidade - $oldQuantidade;

        if ($diff === 0) {
            return;
        }

        if ($diff > 0) {
            if (! $itemPedido->produto->reduzirEstoque($diff)) {
                throw new \RuntimeException('Estoque insuficiente para atualizar a quantidade');
            }

            return;
        }

        $itemPedido->produto->aumentarEstoque(abs($diff));
    }

    public function deleting(ItemPedido $itemPedido)
    {
        $itemPedido->produto->aumentarEstoque($itemPedido->quantidade);
    }

    public function saved(ItemPedido $itemPedido)
    {
        $itemPedido->pedido->atualizarValorTotal();
    }

    public function deleted(ItemPedido $itemPedido)
    {
        $itemPedido->pedido->atualizarValorTotal();
    }
}
