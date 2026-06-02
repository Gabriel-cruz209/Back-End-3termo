<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmação de pedido</title>
</head>
<body style="margin: 0; padding: 0; background: #f8fafc; color: #111827; font-family: Arial, sans-serif;">
    <div style="max-width: 720px; margin: 0 auto; padding: 32px 16px;">
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
            <div style="background: #2563eb; color: #ffffff; padding: 24px;">
                <h1 style="margin: 0; font-size: 24px;">Pedido confirmado</h1>
                <p style="margin: 8px 0 0; font-size: 14px;">Sistema de Pedidos - Pedido #{{ $pedido->id }}</p>
            </div>

            <div style="padding: 24px;">
                <p style="font-size: 16px; line-height: 1.5; margin: 0 0 16px;">
                    Olá, {{ $cliente?->nome ?? 'cliente' }}.
                </p>

                <p style="font-size: 16px; line-height: 1.5; margin: 0 0 24px;">
                    Seu pedido foi cadastrado com sucesso. Agradecemos pela preferência.
                    Abaixo estão as informações do pedido.
                </p>

                <div style="background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                    <p style="margin: 0 0 8px;"><strong>Cliente:</strong> {{ $cliente?->nome ?? '-' }}</p>
                    <p style="margin: 0 0 8px;"><strong>Número do pedido:</strong> #{{ $pedido->id }}</p>
                    <p style="margin: 0 0 8px;"><strong>Data do pedido:</strong> {{ $pedido->created_at?->format('d/m/Y H:i') }}</p>
                    <p style="margin: 0;"><strong>Status:</strong> {{ $pedido->status }}</p>
                </div>

                <h2 style="font-size: 18px; margin: 0 0 12px;">Produtos do pedido</h2>

                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr>
                            <th align="left" style="border-bottom: 1px solid #e5e7eb; padding: 10px 8px;">Produto</th>
                            <th align="center" style="border-bottom: 1px solid #e5e7eb; padding: 10px 8px;">Quantidade</th>
                            <th align="right" style="border-bottom: 1px solid #e5e7eb; padding: 10px 8px;">Valor unitário</th>
                            <th align="right" style="border-bottom: 1px solid #e5e7eb; padding: 10px 8px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pedido->itens as $item)
                            <tr>
                                <td style="border-bottom: 1px solid #f1f5f9; padding: 10px 8px;">
                                    {{ $item->produto?->nome ?? 'Produto não encontrado' }}

                                    @if ($item->produto?->referencia)
                                        <br>
                                        <span style="color: #64748b; font-size: 12px;">Referência: {{ $item->produto->referencia }}</span>
                                    @endif
                                </td>
                                <td align="center" style="border-bottom: 1px solid #f1f5f9; padding: 10px 8px;">
                                    {{ $item->quantidade }}
                                </td>
                                <td align="right" style="border-bottom: 1px solid #f1f5f9; padding: 10px 8px;">
                                    R$ {{ number_format((float) $item->preco_unitario, 2, ',', '.') }}
                                </td>
                                <td align="right" style="border-bottom: 1px solid #f1f5f9; padding: 10px 8px;">
                                    R$ {{ number_format((float) $item->subtotal, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="border-bottom: 1px solid #f1f5f9; padding: 10px 8px; color: #64748b;">
                                    Nenhum item encontrado para este pedido.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" align="right" style="padding: 16px 8px; font-size: 16px;">
                                <strong>Valor total</strong>
                            </td>
                            <td align="right" style="padding: 16px 8px; font-size: 16px; color: #16a34a;">
                                <strong>R$ {{ number_format((float) $pedido->valor_total, 2, ',', '.') }}</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <p style="font-size: 14px; line-height: 1.5; color: #64748b; margin: 24px 0 0;">
                    Esta mensagem foi enviada automaticamente pelo sistema de pedidos.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
