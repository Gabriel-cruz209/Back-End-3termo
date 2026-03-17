<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar Estoque</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('estoque.update', $estoque->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT') <!-- OBRIGATÓRIO PARA EDIÇÃO -->

                    <div>
                        <label class="block text-sm font-medium text-gray-700">nome_produto</label>
                        <input type="text" name="nome_produto" value="{{ $estoque->nome_produto }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">nome_fornecedor</label>
                            <input type="text" name="nome_fornecedor" value="{{ $estoque->nome_fornecedor }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">preco_custo</label>
                            <input type="text" name="preco_custo" value="{{ $estoque->preco_custo }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">quantidade</label>
                            <input type="text" name="quantidade" value="{{ $estoque->quantidade }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">data Validade</label>
                            <input type="text" name="data_validade" placeholder="dd/mm/aaaa" value="{{ \Carbon\Carbon::parse($estoque->data_validade)->format('d/m/Y') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('data_validade') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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