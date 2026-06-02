<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use App\Mail\PedidoConfirmacaoMail;
use App\Models\Pedido;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CreatePedido extends CreateRecord
{
    protected static string $resource = PedidoResource::class;

    public function getTitle(): string
    {
        return 'Novo pedido';
    }

    protected function afterCreate(): void
    {
        $pedido = $this->record;

        $pedido->load(['cliente', 'itens.produto']);

        $total = $pedido->itens->sum(function ($item) {
            return $item->quantidade * $item->preco_unitario;
        });

        $pedido->update(['valor_total' => $total]);
        $pedido->refresh()->load(['cliente', 'itens.produto']);

        $this->enviarEmailConfirmacao($pedido);
    }

    private function enviarEmailConfirmacao(Pedido $pedido): void
    {
        $cliente = $pedido->cliente;

        if (! $cliente) {
            Log::info('E-mail de confirmação de pedido não enviado: cliente não encontrado.', [
                'pedido_id' => $pedido->id,
            ]);

            return;
        }

        if (! $this->emailValido($cliente->email)) {
            Log::info('E-mail de confirmação de pedido não enviado: cliente sem e-mail válido.', [
                'pedido_id' => $pedido->id,
                'cliente_id' => $cliente->id,
                'email' => $cliente->email,
            ]);

            return;
        }

        $logContext = [
            'pedido_id' => $pedido->id,
            'cliente_id' => $cliente->id,
            'email' => $cliente->email,
            'valor_total' => $pedido->valor_total,
        ];

        Log::info('Enviando e-mail de confirmação de pedido para o cliente.', $logContext);

        try {
            Mail::to($cliente->email)->send(new PedidoConfirmacaoMail($pedido));

            Log::info('E-mail de confirmação de pedido enviado com sucesso.', $logContext);
        } catch (Throwable $exception) {
            Log::error('Falha ao enviar e-mail de confirmação de pedido.', [
                ...$logContext,
                'erro' => $exception->getMessage(),
            ]);
        }
    }

    private function emailValido(?string $email): bool
    {
        return filled($email) && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
