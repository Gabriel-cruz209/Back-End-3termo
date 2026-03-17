<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar Pedido</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('pedidos.update', $pedido->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT') <!-- OBRIGATÓRIO PARA EDIÇÃO -->

                    <div>
                        <label class="block text-sm font-medium text-gray-700"># Pedido</label>
                        <input type="text" name="numero_pedido" value="{{ $pedido->numero_pedido }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">valor total</label>
                            <input type="text" name="valor_total" value="{{ $pedido->valor_total }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">status</label>
                            <input type="text" name="status" value="{{ $pedido->status }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">metodo pagamento</label>
                            <input type="text" name="metodo_pagamento" value="{{ $pedido->metodo_pagamento }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Observações</label>
                            <input type="text" name="observacoes" value="{{ $pedido->observacoes }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md uppercase text-xs font-bold">Atualizar Dados</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>