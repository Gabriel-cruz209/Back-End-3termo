<x-app-layout>
    <x-slot name='header'>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fornecedores</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900">Fornecedores</h1>
                <span class="text-sm text-gray-600">{{ $fornecedores->count() }} cadastrados</span>
            </div>
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-blue-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CNPJ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CEP</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($fornecedores as $fornecedor)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $fornecedor->nome }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $fornecedor->cnpj }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $fornecedor->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $fornecedor->telefone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $fornecedor->cep }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>