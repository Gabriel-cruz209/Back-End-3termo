<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 space-y-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-6">
                <p class="text-gray-900">{{ __("You're logged in!") }}</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-6">
                <p class="text-gray-900 mb-4">{{ __("Acesso rápido às seções do sistema") }}</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('clientes.index') }}"
                       class="flex items-center px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Clientes
                    </a>
                    <a href="{{ route('pedidos.index') }}"
                       class="flex items-center px-6 py-3 bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                        Pedidos
                    </a>
                    <a href="{{ route('fornecedores.index') }}"
                       class="flex items-center px-6 py-3 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        Fornecedores
                    </a>
                    <a href="{{ route('estoque.index') }}"
                       class="flex items-center px-6 py-3 bg-green-600 text-white rounded hover:bg-green-700 transition">
                        Estoque
                    </a>
                    <a href="{{ route('produtos.index') }}"
                       class="flex items-center px-6 py-3 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition">
                        Produtos
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>