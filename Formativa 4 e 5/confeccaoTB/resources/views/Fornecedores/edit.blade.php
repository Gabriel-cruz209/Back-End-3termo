<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar Fornecedores</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('fornecedores.update', $fornecedor->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT') <!-- OBRIGATÓRIO PARA EDIÇÃO -->

                    <div>
                        <label class="block text-sm font-medium text-gray-700">nome</label>
                        <input type="text" name="nome" value="{{ $fornecedor->nome }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">cnpj</label>
                            <input type="text" name="cnpj" value="{{ $fornecedor->cnpj }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">telefone</label>
                            <input type="text" name="telefone" value="{{ $fornecedor->telefone }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">email</label>
                            <input type="text" name="email" value="{{ $fornecedor->email }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">endereco</label>
                            <input type="text" name="endereco" value="{{ $fornecedor->endereco ?? '' }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
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