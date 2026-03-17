<x-app-layout>
    <x-slot name='header'>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-gradient-to-br from-green-500 to-green-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v2m0 0v2m0-2h2m-2 0h-2"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-black">Estoque</h2>
                    <p class="text-sm text-black-100">Controlar inventário</p>
                </div>
            </div>
            <a href="{{ route('estoque.create') }}"
                class="inline-flex items-center px-4 py-2 bg-white text-green-600 border border-transparent rounded-lg font-semibold text-sm hover:bg-green-50 hover:shadow-lg transition-all duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Novo Item
            </a>
        </div>
    </x-slot>

    {{-- Alpine.js State para gerenciar o Modal de Estoque --}}
    <div class="py-12" x-data="{ openDeleteModal: false, postUrl: '', produtoNome: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden rounded-xl shadow-lg">
                <div class="p-6 bg-gradient-to-r from-green-50 to-green-100 border-b border-green-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">📦 Itens em Estoque</h3>
                            <p class="mt-1 text-sm text-slate-600">{{ $estoque->count() }} itens cadastrados no sistema</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-green-500 to-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-green-50">Produto</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-green-50">Fornecedor</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-green-50">Quantidade</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-green-50">Validade</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-green-50">Preço Custo</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-green-50">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($estoque as $estq)
                            <tr class="hover:bg-green-50 transition-colors duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">{{ $estq->nome_produto }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $estq->nome_fornecedor }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $estq->quantidade > 10 ? 'bg-green-100 text-green-700' : ($estq->quantidade > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        📊 {{ $estq->quantidade }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ \Carbon\Carbon::parse($estq->data_validade)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-semibold">R$ {{ number_format($estq->preco_custo, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3 opacity-70 group-hover:opacity-100 transition-opacity duration-150">
                                        <a href="{{ route('estoque.edit', $estq->id)}}" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition duration-150">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        {{-- Botão Delete --}}
                                        <button type="button" 
                                            @click="openDeleteModal = true; postUrl = '{{ route('estoque.destroy', $estq->id) }}'; produtoNome = '{{ $estq->nome_produto }}'"
                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition duration-150">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-4 bg-green-100 rounded-full mb-4">
                                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v2m0 0v2m0-2h2m-2 0h-2"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-slate-900 mb-1">Nenhum item em estoque</h4>
                                        <p class="text-sm text-slate-500 mb-4">Comece adicionando seu primeiro item</p>
                                        <a href="{{ route('estoque.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Cadastrar primeiro item
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Estrutura do Modal de Deleção --}}
        <div x-show="openDeleteModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="openDeleteModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Excluir Item do Estoque</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Você está prestes a remover <span class="font-bold text-gray-800" x-text="produtoNome"></span>. Esta ação não pode ser desfeita e o saldo deste item será zerado.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form :action="postUrl" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Confirmar Exclusão
                            </button>
                        </form>
                        <button type="button" @click="openDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>